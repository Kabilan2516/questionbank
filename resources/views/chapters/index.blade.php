@extends('layouts.app')

@section('content')
<h2>Chapters</h2>
<a href="{{ route('chapters.create') }}" class="btn btn-success mb-3">Add Chapter</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Subject</th>
        <th>Actions</th>
    </tr>
    @foreach($chapters as $chapter)
    <tr>
        <td>{{ $chapter->id }}</td>
        <td>{{ $chapter->name }}</td>
        <td>{{ $chapter->subject->name }}</td>
        <td>
            <a href="{{ route('chapters.edit', $chapter) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('chapters.destroy', $chapter) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
