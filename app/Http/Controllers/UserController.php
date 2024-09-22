<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return User::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nom' => 'required|max:100',
            'prenom' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'type' => 'required|in:client,gestionnaire,administrateur',
        ]);

        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = $request->type;
        $user->save();

        return response()->json($user, 201);
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'required|max:100',
            'prenom' => 'required|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'type' => 'required|in:client,gestionnaire,administrateur',
        ]);

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->type = $request->type;
        $user->save();

        return response()->json($user, 200);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
    public function getProprietaire()
    {
        $propio = User::where('profil', "3")->get();
        return response()->json([
            'statut' => 201,
            'data' => $propio
        ]);
    }
}
