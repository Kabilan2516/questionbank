@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Question(s)</h2>

    {{-- JSON Import Form --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('questions.importJson') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-md-8">
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Upload JSON</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Questions Form --}}
    <form action="{{ route('questions.store') }}" method="POST" id="questionForm">
        @csrf

        {{-- Subject & Chapter --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Subject</label>
                <select name="subject_id" class="form-select" required>
                    <option value="">Select Subject</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Chapter</label>
                <select name="chapter_id" class="form-select">
                    <option value="">Select Chapter</option>
                    @foreach ($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr>

        {{-- Questions Wrapper --}}
        <div id="questions_wrapper">
            @if (isset($questions) && count($questions) > 0)
                @foreach ($questions as $i => $q)
                <div class="card mb-3 question_block" data-index="{{ $i }}">
                    <div class="card-body">
                        <h5 class="card-title">Question {{ $i + 1 }}</h5>

                        <div class="mb-3">
                            <label class="form-label">Question Text</label>
                            <textarea name="questions[{{ $i }}][question_text]" class="form-control" rows="2" required>{{ $q['question_text'] }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Question Type</label>
                                <select name="questions[{{ $i }}][question_type]" class="form-select question_type" required>
                                    <option value="MCQ" selected>MCQ</option>
                                    <option value="True_False">True / False</option>
                                    <option value="Fill_Blank">Fill in the Blank</option>
                                    <option value="Descriptive">Descriptive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Question Format</label>
                                <select name="questions[{{ $i }}][question_format]" class="form-select" required>
                                    <option value="Single_MC" @if($q['question_format'] == 'Single_MC') selected @endif>Single Choice</option>
                                    <option value="Multiple_MC" @if($q['question_format'] == 'Multiple_MC') selected @endif>Multiple Choice</option>
                                </select>
                            </div>
                        </div>

                        {{-- Options --}}
                        <div class="mcq_section">
                            <label class="form-label">Options</label>
                            <div class="options_wrapper">
                                @foreach ($q['options'] as $j => $opt)
                                <div class="input-group mb-2 option_row">
                                    <input type="text" name="questions[{{ $i }}][options][{{ $j }}][option_text]" 
                                           value="{{ $opt['option_text'] }}" class="form-control" placeholder="Option text" required>
                                    <div class="input-group-text">
                                        <input type="checkbox" name="questions[{{ $i }}][options][{{ $j }}][is_correct]" 
                                               value="1" @if($opt['is_correct']) checked @endif>
                                    </div>
                                    <button type="button" class="btn btn-danger remove_option">Remove</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary add_option">Add Option</button>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                {{-- Default empty question block --}}
                <div class="card mb-3 question_block" data-index="0">
                    <div class="card-body">
                        <h5 class="card-title">Question 1</h5>
                        <div class="mb-3">
                            <textarea name="questions[0][question_text]" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <select name="questions[0][question_type]" class="form-select question_type" required>
                                    <option value="MCQ" selected>MCQ</option>
                                    <option value="True_False">True / False</option>
                                    <option value="Fill_Blank">Fill in the Blank</option>
                                    <option value="Descriptive">Descriptive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="questions[0][question_format]" class="form-select" required>
                                    <option value="Single_MC">Single Choice</option>
                                    <option value="Multiple_MC">Multiple Choice</option>
                                </select>
                            </div>
                        </div>

                        <div class="mcq_section">
                            <div class="options_wrapper">
                                <div class="input-group mb-2 option_row">
                                    <input type="text" name="questions[0][options][0][option_text]" class="form-control" placeholder="Option text" required>
                                    <div class="input-group-text">
                                        <input type="checkbox" name="questions[0][options][0][is_correct]" value="1">
                                    </div>
                                    <button type="button" class="btn btn-danger remove_option">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary add_option">Add Option</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <button type="button" id="add_question" class="btn btn-success mb-3">Add Another Question</button>
        <button type="submit" class="btn btn-primary">Save Question(s)</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionsWrapper = document.getElementById('questions_wrapper');

    document.getElementById('add_question').addEventListener('click', function() {
        const index = questionsWrapper.children.length;
        const block = document.createElement('div');
        block.className = 'card mb-3 question_block';
        block.dataset.index = index;
        block.innerHTML = `
            <div class="card-body">
                <h5 class="card-title">Question ${index + 1}</h5>
                <div class="mb-3">
                    <textarea name="questions[${index}][question_text]" class="form-control" rows="2" required></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <select name="questions[${index}][question_type]" class="form-select question_type" required>
                            <option value="MCQ" selected>MCQ</option>
                            <option value="True_False">True / False</option>
                            <option value="Fill_Blank">Fill in the Blank</option>
                            <option value="Descriptive">Descriptive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="questions[${index}][question_format]" class="form-select" required>
                            <option value="Single_MC">Single Choice</option>
                            <option value="Multiple_MC">Multiple Choice</option>
                        </select>
                    </div>
                </div>
                <div class="mcq_section">
                    <div class="options_wrapper">
                        <div class="input-group mb-2 option_row">
                            <input type="text" name="questions[${index}][options][0][option_text]" class="form-control" placeholder="Option text" required>
                            <div class="input-group-text">
                                <input type="checkbox" name="questions[${index}][options][0][is_correct]" value="1">
                            </div>
                            <button type="button" class="btn btn-danger remove_option">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary add_option">Add Option</button>
                </div>
            </div>
        `;
        questionsWrapper.appendChild(block);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove_option')) {
            const row = e.target.closest('.option_row');
            if (row) row.remove();
        }

        if (e.target.classList.contains('add_option')) {
            const mcqSection = e.target.closest('.mcq_section');
            const optionsWrapper = mcqSection.querySelector('.options_wrapper');
            const blockIndex = mcqSection.closest('.question_block').dataset.index;
            const optionIndex = optionsWrapper.children.length;

            const row = document.createElement('div');
            row.className = 'input-group mb-2 option_row';
            row.innerHTML = `
                <input type="text" name="questions[${blockIndex}][options][${optionIndex}][option_text]" class="form-control" placeholder="Option text" required>
                <div class="input-group-text">
                    <input type="checkbox" name="questions[${blockIndex}][options][${optionIndex}][is_correct]" value="1">
                </div>
                <button type="button" class="btn btn-danger remove_option">Remove</button>
            `;
            optionsWrapper.appendChild(row);
        }
    });
});
</script>
@endsection
