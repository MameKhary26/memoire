<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // public function inscription(Request $request)
    // {
    //     // $request->validate([
    //     //     'nom' => 'required|string|max:255',
    //     //     'prenom' => 'required|string|max:255',
    //     //     'email' => 'required|string|email|max:255|unique:users',
    //     //     'profil' => 'required|string|max:255',
    //     //     'motPasse' => 'required|string|min:6',
    //     // ]);

    //     $user = User::create([
    //         'nom' => $request->nom,
    //         'prenom' => $request->prenom,
    //         'email' => $request->email,
    //         'motPasse' => Hash::make($request->motPasse),
    //         'profil' => 0,
    //     ]);

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json(['token' => $token, 'users' => $user]);

    //     return view('login');

    // }


    public function inscription(Request $request)
    {
        // Valider les données du formulaire
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'motPasse' => 'required|string|min:6',
        ]);

        try {
            // Hash du mot de passe avant de le stocker
            $data['motPasse'] = Hash::make($data['motPasse']);

            // Définir le statut par défaut à "debloquer"
            $data['profil'] = 1;

            // Création de l'utilisateur
            $user = User::create($data);

            // Génération du token JWT (si nécessaire, sinon laisser null)
            // $token = JWTAuth::fromUser($user);

            // Réponse avec les données de l'utilisateur et le token
            return response()->json([
                'statut' => 201,
                'data' => $user,
                "token" => null,
            ], 201);

        } catch (\Exception $e) {
            // En cas d'erreur, retourne un message d'erreur
            return response()->json([
                "statut" => false,
                "message" => "Erreur lors de l'inscription",
                "error" => $e->getMessage()
            ], 500);
        }
    }




    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required',
    //         'motPasse' => 'required',
    //     ]);
        

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->motPasse, $user->motPasse)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json(['token' => $token, 'users' => $user]);

    //     return view ('accueil');
    // }

//     public function login(Request $request)
// {
//     // Valider les données
//     $request->validate([
//         'email' => 'required|email',
//         'motPasse' => 'required'
//     ]);

//     $credentials = $request->only('email', 'motPasse');

//     if (Auth::attempt($credentials)) {
//         $user = Auth::user();
//         $token = $user->createToken('auth_token')->accessToken; // Laravel Passport ou Sanctum
//         return response()->json(['token' => $token], 200);
//     } else {
//         return response()->json(['message' => 'Invalid credentials'], 401);
//     }
// }

public function login(Request $request)
{
    // Valider les données d'entrée
    $request->validate([
        'email' => 'required|email',
        'motPasse' => 'required',
    ]);

    // Rechercher l'utilisateur par email
    $user = User::where('email', $request->email)->first();

    // Vérifier si l'utilisateur existe
    if (!$user) {
        return response()->json([
            'message' => 'Utilisateur non trouvé.',
        ], 404);
    }

    // Vérifier si le mot de passe est correct
    if (!Hash::check($request->motPasse, $user->motPasse)) {
        return response()->json([
            'message' => 'Mot de passe incorrect.',
        ], 401);
    }

    // Générer un token JWT pour l'utilisateur
    if (!$token = JWTAuth::fromUser($user)) {
        return response()->json([
            'message' => 'Erreur lors de la génération du token.',
        ], 500);
    }

    // Retourner la réponse avec le token et les informations de l'utilisateur
    return response()->json([
        'message' => 'Connexion réussie.',
        'token' => $token,
        'user' => $user,
    ], 200);
}


// public function login(Request $request) {

//     $data = $request->validate([

//         "email" => "required|email|",
//         "motPasse" => "required"
//     ]);

//     $token = JWTAuth::attempt($data);

//     if(!empty($token))
//     {
//         return response()->json([
//             'statut' => 200,
//             'data' => auth()->user(),
//             "token" => $token,
//         ]);
//     }else{
//         return response()->json([
//             "statut" => false,
//             "token" => null
//         ]);

//         return response()->json(compact('token'));
//     }

    // public function me()
    // {
    //     return response()->json(auth()->user());
    // }
    // $credentials = $request->only('email', 'motPasse');
    
    // if (Auth::attempt($credentials)) {
    //     $user = Auth::user();
    //     $token = $user->createToken('authToken')->plainTextToken;
    //     return response()->json(['token' => $token], 200);
    // }

    // return response()->json(['message' => 'Invalid credentials'], 401);
}
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'motPasse');
        
    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();
    //         $token = $user->createToken('auth_token')->accessToken; // Utilisation de Passport ou Sanctum
    //         return response()->json(['token' => $token,'user' => $user], 200);
    //     } else {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }
    // }}

