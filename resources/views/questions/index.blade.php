@extends('layouts.app')

@section('content')
<h2>Questions</h2>
<a href="{{ route('questions.create') }}" class="btn btn-success mb-3">Add Question</a>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
<tr>
    <th>ID</th>
    <th>Question</th>
    <th>Type</th>
    <th>Subject</th>
    <th>Chapter</th>
    <th>Actions</th>
</tr>
@foreach($questions as $q)
<tr>
    <td>{{ $q->id }}</td>
    <td>{{ $q->question_text }}</td>
    <td>{{ $q->question_type }}</td>
    <td>{{ $q->subject->name }}</td>
    <td>{{ $q->chapter?->name }}</td>
    <td>
        <a href="{{ route('questions.edit', $q) }}" class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('questions.destroy', $q) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
        </form>
    </td>
</tr>
@endforeach
</table>
@endsection
