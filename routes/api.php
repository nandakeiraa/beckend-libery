<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Login)
|--------------------------------------------------------------------------
*/

// LOGIN
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // INFO USER LOGIN
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // CRUD BUKU
        Route::apiResource('/books', BookController::class);

        // CRUD ANGGOTA (USER ROLE SISWA)
        Route::apiResource('/users', UserController::class);

        // LIHAT SEMUA TRANSAKSI
        Route::get('/transactions', [TransactionController::class, 'index']);
    });

    /*
    |--------------------------------------------------------------------------
    | SISWA ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:siswa')->group(function () {

        // LIHAT SEMUA BUKU
        Route::get('/books', [BookController::class, 'index']);

        // PINJAM BUKU
        Route::post('/borrow', [TransactionController::class, 'borrow']);

        // KEMBALIKAN BUKU
        Route::post('/return/{id}', [TransactionController::class, 'returnBook']);

        // RIWAYAT PINJAM SISWA
        Route::get('/my-transactions', [TransactionController::class, 'myTransactions']);
    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);
});
