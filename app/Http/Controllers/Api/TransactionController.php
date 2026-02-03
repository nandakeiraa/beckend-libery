<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function borrow(Request $request)
    {
        $book = Book::findOrFail($request->book_id);

        if ($book->stok < 1) {
            return response()->json(['message' => 'Stok habis'], 400);
        }

        Transaction::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam'
        ]);

        $book->decrement('stok');

        return response()->json(['message' => 'Berhasil meminjam']);
    }
    
    public function returnBook($id)
    {
        $trx = Transaction::findOrFail($id);

        $trx->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan'
        ]);

        $trx->book->increment('stok');

        return response()->json(['message' => 'Buku dikembalikan']);
    }
}
