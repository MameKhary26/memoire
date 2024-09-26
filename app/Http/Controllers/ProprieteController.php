<?php

namespace App\Http\Controllers;

use App\Models\Propriete;
use Illuminate\Http\Request;

class ProprieteController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les critères de recherche
        $adresse = $request->input('adresse');
        $typeProriete = $request->input('typePropriete');
        $prix = $request->input('prix');
        $superficie = $request->input('superficie');

        // Construire la requête avec des filtres conditionnels
        $query = Propriete::query();

        if ($adresse) {
            $query->where('adresse', 'LIKE', '%' . $adresse . '%');
        }

        if ($typeProriete) {
            $query->where('typePropriete', $typeProriete);
        }

        if ($prix) {
            $query->where('prix', '>=', $prix);
        }

        if ($superficie) {
            $query->where('superficie', '>=', $superficie);
        }
        // Exécuter la requête et retourner les résultats
        $query->where('statutTransaction ', 'disponible');
        $biens = $query->get();
        return response()->json($biens);
    }

    public function getDispo()
    {
        $proprietes = Propriete::where('statutPropriete','Disponible')->get();
        return response()->json($proprietes);
    }

    public function indexB()
    {
        return Propriete::all(); // Retourne la liste des biens
    }

    // Créer un bien
    public function store(Request $request)
    {
        // $request->validate([
        //     'typePropriete' => 'required',
        //     'adresse' => 'required',
        //     'superficie' => 'required',
        //     'description' => 'nullable',
        //     'prix' => 'required|numeric',
        //     'statutPropriete' => 'required',
        // ]);

        $bien = Propriete::create($request->all());
        return response()->json($bien, 201); // Bien créé avec succès
    }

    // Lire un bien spécifique
    public function show($id)
    {
        $bien = Propriete::find($id);
        if (!$bien) {
            return response()->json(['message' => 'Bien non trouvé'], 404);
        }
        return response()->json($bien);
    }

    // Mettre à jour un bien
    public function update(Request $request, $id)
    {
        $bien = Propriete::find($id);
        if (!$bien) {
            return response()->json(['message' => 'Bien non trouvé'], 404);
        }

        $request->validate([
            'typePropriete' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'user_id' => 'required|string|max:255',
            'superficie' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric',
            'statutPropriete' => 'required|in:Disponible,Loué,Vendu',
        ]);

        $bien->update($request->all());
        return response()->json($bien);
    }

    // Supprimer un bien
    public function destroy($id)
    {
        $bien = Propriete::find($id);
        if (!$bien) {
            return response()->json(['message' => 'Bien non trouvé'], 404);
        }
        $bien->delete();
        return response()->json(['message' => 'Bien supprimé avec succès']);
    }
    // public function getProprietaire()
    // {
    //     $propio = User::where("profile","3")->get();
    //     return response()->json($propio);
    // }
}
