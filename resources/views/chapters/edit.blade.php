@extends('layouts.app')

@section('content')
<h2>Edit Chapter</h2>

<form action="{{ route('chapters.update', $chapter) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Chapter Name</label>
        <input type="text" name="name" class="form-control" value="{{ $chapter->name }}" required>
    </div>

    <div class="mb-3">
        <label>Subject</label>
        <select name="subject_id" class="form-control" required>
            <option value="">Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" @if($chapter->subject_id == $subject->id) selected @endif>{{ $subject->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('chapters.index') }}" class="btn btn-secondary">Back</a>
</form>
@endsection
