<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Models\Subject;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chapters = Chapter::with('subject')->get();
        return view('chapters.index', compact('chapters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::all(); // dropdown for subject
        return view('chapters.create', compact('subjects'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        Chapter::create($request->all());
        return redirect()->route('chapters.index')->with('success', 'Chapter created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        $subjects = Subject::all();
        return view('chapters.edit', compact('chapter', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapter $chapter)
    {
        $request->validate([
            'name' => 'required',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $chapter->update($request->all());
        return redirect()->route('chapters.index')->with('success', 'Chapter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return redirect()->route('chapters.index')->with('success', 'Chapter deleted successfully.');
    }
}
