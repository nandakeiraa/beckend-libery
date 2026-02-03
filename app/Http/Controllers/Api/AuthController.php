<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller

{
   public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('username', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Login gagal'], 401);
    }

    $token = $user->createToken('token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
}
}
