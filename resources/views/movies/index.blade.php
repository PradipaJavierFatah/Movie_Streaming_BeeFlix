@extends('layouts.app')

@section('content')
<div class="container">
    <h1>BeeFlix</h1>

    <!-- Form Searching -->
        <form method="GET" action="{{ route('movies.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search movies...">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <a href="{{ route('movies.create') }}" class="btn btn-primary mb-3">Add Movie</a>
    {{-- <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Description</th>
                <th>Publish Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movies as $movie)
            <tr>
                <td>
                    <img src="{{ asset('storage/' . $movie->photo) }}" alt="{{ $movie->title }}" style="width: 100px; height: auto;">
                </td>
                <td>{{ $movie->title }}</td>
                <td>{{ $movie->genre->name }}</td>
                <td>{{ $movie->description }}</td>
                <td>{{ $movie->publish_date }}</td>
                <td>
                    <form action="{{ route('movies.destroy', $movie->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                        <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-warning btn-warning btn-sm">Edit</a>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table> --}}

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($movies as $movie)
            <div class="col">
                <div class="card h-100 shadow-sm rounded">
                    <img src="{{ asset('storage/' . $movie->photo) }}" class="card-img-top" alt="{{ $movie->title }}" style="width: 100%; height: 400px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $movie->title }}</h5>
                        <p><strong>Genre:</strong> {{ $movie->genre->name }}</p>
                        <p><strong>Published on:</strong> {{ \Carbon\Carbon::parse($movie->publish_date)->format('d-m-Y') }}</p>
                        <p class="card-text"> <strong>Description:</strong>{{ $movie->description }}</p>
                        <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-warning">Edit</a>

                        <!-- Tombol Hapus dengan Konfirmasi -->
                        <form action="{{ route('movies.destroy', $movie->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $movie->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $movie->id }})">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $movies->links() }}
    </div>


</div>

@endsection

@section('scripts')
<script>
    // Fungsi untuk mengonfirmasi penghapusan
    function confirmDelete(movieId) {
        if (confirm("Are you sure you want to delete this movie?")) {
            // Jika pengguna mengonfirmasi, kirim formulir penghapusan
            document.getElementById('delete-form-' + movieId).submit();
        }
    }
</script>
@endsection
