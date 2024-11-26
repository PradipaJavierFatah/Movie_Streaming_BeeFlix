<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    //
    public function index(Request $request)
    {
        // Ambil nilai pencarian dari query string
        $search = $request->input('search');

        // Query untuk mendapatkan data movie yang relevan dengan pencarian
        $movies = Movie::with('genre')
            ->when($search, function ($query, $search) {
                return $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('genre', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(4); // Paginate hasil pencarian

        return view('movies.index', compact('movies'));
    }

    public function create()
    {
        $genres = Genre::all(); // Mengambil semua data genre
        return view('movies.create', compact('genres'));
    }

    public function store(Request $request)
    {
        // Validasi Manual
        $validator = Validator::make(
            $request->all(),
            [
                'genre_id' => 'required|exists:genres,id',
                'photo' => 'required|image|max:5120',
                'title' => 'required|max:30',
                'description' => 'required|max:50',
                'publish_date' => 'required|date|before_or_equal:today',
            ],
            [
                'genre_id.required' => 'Genre wajib dipilih.',
                'genre_id.exists' => 'Genre yang dipilih tidak valid.',
                'photo.required' => 'Photo wajib diunggah.',
                'photo.image' => 'Photo harus berupa file gambar.',
                'photo.max' => 'Ukuran file maksimal adalah 5MB.',
                'title.required' => 'Title wajib diisi.',
                'title.max' => 'Title maksimal 30 karakter.',
                'description.required' => 'Description wajib diisi.',
                'description.max' => 'Description maksimal 50 karakter.',
                'publish_date.required' => 'Publish date wajib diisi.',
                'publish_date.date' => 'Publish date harus berupa tanggal.',
                'publish_date.before_or_equal' => 'Publish date tidak boleh lebih dari hari ini.',
            ],
        );

        if ($validator->fails()) {
            // Kirim pesan error manual ke view
            return redirect()->route('movies.create')->withErrors($validator)->withInput()->with('custom_errors', $validator->errors()->all());
        }

        try {
            // Simpan file foto
            $photoPath = $request->file('photo')->store('photos', 'public');

            // Simpan data film ke database
            Movie::create([
                'genre_id' => $request->genre_id,
                'title' => $request->title,
                'photo' => $photoPath,
                'publish_date' => $request->publish_date,
                'description' => $request->description,
            ]);

            return redirect()->route('movies.index')->with('success', 'Movie berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('movies.create')->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function edit(Movie $movie)
    {
        $genres = Genre::all(); // Ambil semua genre
        return view('movies.edit', compact('movie', 'genres'));
    }

    public function update(Request $request, Movie $movie)
    {
        // Validasi Input
        $validatedData = $request->validate([
            'genre_id' => 'required|exists:genres,id',
            'photo' => 'nullable|image|max:5120',
            'title' => 'required|max:30',
            'description' => 'required|max:50',
            'publish_date' => 'required|date|before_or_equal:today',
        ]);

        try {
            // Update foto jika ada file foto baru
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if (Storage::disk('public')->exists($movie->photo)) {
                    Storage::disk('public')->delete($movie->photo);
                }

                // Simpan foto baru
                $photoPath = $request->file('photo')->store('photos', 'public');
                $validatedData['photo'] = $photoPath;
            }

            // Update data film
            $movie->update($validatedData);

            return redirect()->route('movies.index')->with('success', 'Movie berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->route('movies.edit', $movie->id)
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy(Movie $movie)
    {
        if (Storage::disk('public')->exists($movie->photo)) {
            Storage::disk('public')->delete($movie->photo);
        }
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Movie berhasil dihapus!');
    }
}
