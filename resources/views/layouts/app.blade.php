<!DOCTYPE html>
<html>
<head>
    <title>TNPSC Question Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-3">
    <nav class="mb-3">
        <a href="{{ route('subjects.index') }}" class="btn btn-sm btn-info">Subjects</a>
        <a href="{{ route('chapters.index') }}" class="btn btn-sm btn-info">Chapters</a>
        <a href="{{ route('questions.index') }}" class="btn btn-sm btn-info">Questions</a>
        <a href="{{ route('tests.index') }}" class="btn btn-sm btn-info">Tests</a>
    </nav>
    @yield('content')
</div>

{{-- Include page-specific scripts --}}
@yield('scripts')

</body>
</html>
