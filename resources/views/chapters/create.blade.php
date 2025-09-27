@extends('layouts.app')

@section('content')
<h2>Add Chapter</h2>

<form action="{{ route('chapters.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Chapter Name</label>
        <input type="text" name="name" class="form-control" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Subject</label>
        <select name="subject_id" class="form-control" required>
            <option value="">Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
        @error('subject_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('chapters.index') }}" class="btn btn-secondary">Back</a>
</form>
@endsection
