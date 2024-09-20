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
        $biens = $query->get();
        return response()->json($biens);
    }
}
