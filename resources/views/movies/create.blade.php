@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Movie</h1>

    <!-- Tampilkan Pesan Error Jika Ada -->
    @if(session('custom_errors'))
        <div class="alert alert-danger">
            <strong>Oops! Periksa input Anda:</strong>
            <ul>
                @foreach (session('custom_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tampilkan Alert Success Jika Ada -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="genre_id" class="form-label">Genre</label>
            <select class="form-select" name="genre_id" id="genre_id">
                <option value="">--Select Genre--</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" maxlength="30">
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" name="photo" id="photo" accept="image/*" >
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" maxlength="50" >{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="publish_date" class="form-label">Publish Date</label>
            <input type="date" class="form-control" name="publish_date" id="publish_date" value="{{ old('publish_date') }}">
        </div>
        <button class="btn btn-success">Submit</button>
        <a href="{{ route('movies.index') }}" class="btn btn-warning">Back</a>
    </form>
</div>
@endsection
