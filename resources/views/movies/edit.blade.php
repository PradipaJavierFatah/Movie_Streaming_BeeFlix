@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Movie</h1>

    <!-- Tampilkan Alert Error Jika Validasi Gagal -->
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Oops! There were some errors with your input:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="genre_id" class="form-label">Genre</label>
            <select class="form-select" name="genre_id" id="genre_id">
                <option value="">--Select Genre--</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ old('genre_id', $movie->genre_id) == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $movie->title) }}" maxlength="30" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
            <img src="{{ asset('storage/' . $movie->photo) }}" alt="Current Photo" width="100" class="mt-2">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" maxlength="50" required>{{ old('description', $movie->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="publish_date" class="form-label">Publish Date</label>
            <input type="date" class="form-control" name="publish_date" id="publish_date" value="{{ old('publish_date', $movie->publish_date) }}" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('movies.index') }}" class="btn btn-warning">Back</a>
    </form>
</div>
@endsection
