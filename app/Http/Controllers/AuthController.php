<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function inscription(Request $request)
    {
        // $request->validate([
        //     'nom' => 'required|string|max:255',
        //     'prenom' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'profil' => 'required|string|max:255',
        //     'motPasse' => 'required|string|min:6',
        // ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'motPasse' => Hash::make($request->motPasse),
            'profil' => 1,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'motPasse' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->motPasse, $user->motPasse)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }
}
