<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::with('subject', 'chapter', 'options')->get();
        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::all();
        $chapters = Chapter::all();
        return view('questions.create', compact('subjects', 'chapters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate basic fields
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:MCQ,Descriptive,Fill_Blank,True_False,Match,Assertion_Reason',
            'questions.*.question_format' => 'required|in:Single_MC,Multiple_MC',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*.option_text' => 'required_with:questions.*.options|string',
            'questions.*.options.*.is_correct' => 'nullable|boolean',
        ]);

        $questionsData = $request->input('questions');

        foreach ($questionsData as $qData) {

            // Skip if question already exists
            $exists = Question::where('question_text', $qData['question_text'])
                ->where('subject_id', $request->subject_id)
                ->where('chapter_id', $request->chapter_id)
                ->exists();

            if ($exists) {
                continue; // skip this question
            }

            // Determine answer_text automatically for single-choice MCQ
            $answerText = null;
            if (!empty($qData['options']) && is_array($qData['options'])) {
                $correctOptions = array_filter($qData['options'], fn($opt) => !empty($opt['is_correct']));
                if ($qData['question_format'] === 'Single_MC') {
                    $answerText = count($correctOptions) > 0 ? array_values($correctOptions)[0]['option_text'] : null;
                } elseif ($qData['question_format'] === 'Multiple_MC') {
                    $answerText = implode(',', array_map(fn($opt) => $opt['option_text'], $correctOptions));
                }
            }

            // Create question
            $question = Question::create([
                'question_text' => $qData['question_text'],
                'question_type' => $qData['question_type'],
                'question_format' => $qData['question_format'],
                'answer_text' => $answerText,
                'subject_id' => $request->subject_id,
                'chapter_id' => $request->chapter_id,
                'difficulty' => $qData['difficulty'] ?? 'Medium',
                'marks' => $qData['marks'] ?? 1,
            ]);

            // Handle options if any
            if (!empty($qData['options']) && is_array($qData['options'])) {
                foreach ($qData['options'] as $index => $opt) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $opt['option_text'],
                        'is_correct' => !empty($opt['is_correct']) ? 1 : 0,
                        'option_order' => $index + 1,
                    ]);
                }
            }
        }


        return redirect()->route('questions.index')->with('success', 'Question(s) created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $subjects = Subject::all();
        $chapters = Chapter::all();
        $question->load('options');
        return view('questions.edit', compact('question', 'subjects', 'chapters'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => "required|unique:questions,question_text,$question->id",
            'question_type' => 'required|in:MCQ,Descriptive,Fill_Blank,True_False,Match,Assertion_Reason',
            'question_format' => 'required',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
        ]);

        $question->update($request->only([
            'question_text',
            'question_type',
            'question_format',
            'answer_text',
            'subject_id',
            'chapter_id',
            'difficulty',
            'marks'
        ]));

        // Delete old options and add new
        if ($request->has('options')) {
            $question->options()->delete();
            foreach ($request->options as $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'is_correct' => $option['is_correct'] ?? 0,
                    'option_order' => $option['option_order'] ?? 0
                ]);
            }
        }

        return redirect()->route('questions.index')->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->options()->delete();
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Question deleted successfully.');
    }




    public function importJson(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
        ]);

        $file = $request->file('file');
        $json = file_get_contents($file->getPathName());
        $data = json_decode($json, true);

        $questions = [];

        foreach ($data as $item) {
            $options = [];
            foreach ($item['options'] as $opt) {
                $options[] = [
                    'option_text' => $opt,
                    'is_correct' => ($opt == $item['correct']) ? 1 : 0
                ];
            }

            $questions[] = [
                'question_text' => $item['question'],
                'question_type' => 'MCQ',
                'question_format' => 'Single_MC', // single correct answer
                'options' => $options
            ];
        }

        $subjects = Subject::all();
        $chapters = Chapter::all();

        return view('questions.create', compact('subjects', 'chapters', 'questions'));
    }
}
