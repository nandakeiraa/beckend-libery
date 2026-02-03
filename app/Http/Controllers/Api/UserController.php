<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * GET /api/users
     * Menampilkan semua anggota (siswa)
     */
    public function index()
    {
        $users = User::where('role', 'siswa')->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * POST /api/users
     * Menambahkan anggota baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username',
            'password'  => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil ditambahkan',
            'data'    => $user
        ], 201);
    }

    /**
     * GET /api/users/{id}
     * Detail anggota
     */
    public function show($id)
    {
        $user = User::where('role', 'siswa')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * PUT /api/users/{id}
     * Update anggota
     */
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'siswa')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
        ];

        // kalau password diisi
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil diupdate',
            'data'    => $user
        ]);
    }

    /**
     * DELETE /api/users/{id}
     * Hapus anggota
     */
    public function destroy($id)
    {
        $user = User::where('role', 'siswa')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil dihapus'
        ]);
    }
}
