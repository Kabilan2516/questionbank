@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subjects</h2>
    <a href="{{ route('subjects.create') }}" class="btn btn-primary mb-3">Add Subject</a>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <table class="table table-bordered">
        <tr><th>ID</th><th>Name</th><th>Action</th></tr>
        @foreach($subjects as $subject)
        <tr>
            <td>{{ $subject->id }}</td>
            <td>{{ $subject->name }}</td>
            <td>
                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('subjects.destroy', $subject) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
