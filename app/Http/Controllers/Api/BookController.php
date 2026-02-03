<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * GET /api/books
     * Menampilkan semua buku
     */
    public function index()
    {
        $books = Book::all();

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * POST /api/books
     * Menyimpan buku baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'penulis'   => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'tahun'     => 'required|digits:4',
            'stok'      => 'required|integer|min:0',
        ]);

        $book = Book::create([
            'judul'     => $request->judul,
            'penulis'   => $request->penulis,
            'penerbit'  => $request->penerbit,
            'tahun'     => $request->tahun,
            'stok'      => $request->stok,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $book
        ], 201);
    }

    /**
     * GET /api/books/{id}
     * Menampilkan detail buku
     */
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * PUT /api/books/{id}
     * Update data buku
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'judul'     => 'required|string|max:255',
            'penulis'   => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'tahun'     => 'required|digits:4',
            'stok'      => 'required|integer|min:0',
        ]);

        $book->update([
            'judul'     => $request->judul,
            'penulis'   => $request->penulis,
            'penerbit'  => $request->penerbit,
            'tahun'     => $request->tahun,
            'stok'      => $request->stok,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diupdate',
            'data'    => $book
        ]);
    }

    /**
     * DELETE /api/books/{id}
     * Menghapus buku
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}
