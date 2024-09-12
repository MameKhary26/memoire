<?php

namespace App\Models;
use App\Models\Propriete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propriete extends Model
{
    public function index(Request $request)
    {
        // Récupérer les critères de recherche
        $adresse = $request->input('adresse');
        $type = $request->input('type');
        $prix = $request->input('prix');
        // $prix_max = $request->input('prix_max');
        // $superficie_min = $request->input('superficie_min');
        // $superficie_max = $request->input('superficie_max');

        // Construire la requête avec des filtres conditionnels
        $query = Propriete::query();

        if ($adresse) {
            $query->where('adresse', 'LIKE', '%' . $adresse . '%');
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($prix) {
            $query->where('prix', '>=', $prix);
        }

        // if ($prix_max) {
        //     $query->where('prix', '<=', $prix_max);
        // }

        // if ($superficie_min) {
        //     $query->where('superficie', '>=', $superficie_min);
        // }

        // if ($superficie_max) {
        //     $query->where('superficie', '<=', $superficie_max);
        // }

        // Exécuter la requête et retourner les résultats
        $biens = $query->get();

        return response()->json($biens);
    }
}
