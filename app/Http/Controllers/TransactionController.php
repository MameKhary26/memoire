<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Propriete;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function indexRecup() {
        return Transaction::all();
    }

   
    public function updateTransactionStatus(Request $request, $id)
{
    $request->validate([
        'statutTransaction' => 'required|in:accepté,annulé,en attente',
    ]);

    $transaction = Transaction::findOrFail($id);
    $transaction->statutTransaction = $request->statutTransaction;
    $transaction->save();

    return response()->json([
        'message' => 'Le statut de la transaction a été mis à jour avec succès.',
        'transaction' => $transaction,
    ], 200);
}

    public function store(Request $request)
    {
        

    // Valider les données
    // $request->validate([
    //     'user_id' => 'required',
    //     'propriete_id' => 'required',
    //     'typeTransaction' => 'required',
    //     'montant' => 'required',
    //     'dateTransaction' => 'required',
    //     'statutTransaction' => 'required',
    // ]);

    // Récupérer les données nécessaires
    $user = User::find($request->user_id);
    $propriete = Propriete::find($request->propriete_id);

    // Créer la transaction
    $transaction = new Transaction([
        'user_id' => $user->id,
        'propriete_id' => $propriete->id,
        'typeTransaction' => $request->typeTransaction,
        'statutTransaction' => 'en attente',
        'montant' => $request->montant,
        'dateTransaction' => now(),
    ]);

    $transaction->save();

    return response()->json([
        'message' => 'Transaction de type location effectuée avec succès.',
        'transaction' => $transaction
    ], 201);
}

public function mesTransactions()
{
    // Récupérer l'utilisateur connecté
    $user = auth()->user();

    // Vérifier que l'utilisateur est authentifié
    if (!$user) {
        return response()->json(['error' => 'Utilisateur non authentifié'], 401);
    }

    // Récupérer toutes les transactions effectuées par cet utilisateur
    $transactions = Transaction::where('user_id', $user->id)->get();

    // Retourner les transactions sous forme de JSON
    return response()->json([
        'transactions' => $transactions
    ], 200);
}

public function storeVente(Request $request)
    {

    // Valider les données
    // $request->validate([
    //     'user_id' => 'required',
    //     'propriete_id' => 'required',
    //     'typeTransaction' => 'required',
    //     'montant' => 'required',
    //     'dateTransaction' => 'required',
    //     'statutTransaction' => 'required',
    // ]);

    // Récupérer les données nécessaires
    $user = User::find($request->user_id);
    $propriete = Propriete::find($request->propriete_id);

    // Créer la transaction
    $transaction = new Transaction([
        'user_id' => $user->id,
        'propriete_id' => $propriete->id,
        'typeTransaction' => $request->typeTransaction,
        'statutTransaction' => 'en attente',
        'montant' => $request->montant,
        'dateTransaction' => now(),
    ]);

    $transaction->save();

    return response()->json([
        'message' => 'Transaction de type vente effectuée avec succès.',
        'transaction' => $transaction
    ], 201);
}

    public function index()
    {
        
        return Transaction::all();

        // Récupérer les transactions de l'utilisateur connecté
        // $transactions = Transaction::where('user_id', Auth::id())->get();

        // return response()->json($transactions);
    }

    public function filterByDate(Request $request)
    {
        // Filtrer les transactions par date
        $from = $request->input('from');
        $to = $request->input('to');

        $transactions = Transaction::where('user_id', Auth::id())
            ->whereBetween('dateTransaction', [$from, $to])
            ->get();

        return response()->json($transactions);
    }
    public function downloadPDF()
    {
        $transactions = Transaction::where('user_id', Auth::id())->get();
        $pdf = PDF::loadView('transactions.report', ['transactions' => $transactions]);
        
        return $pdf->download('transactions_report.pdf');
    }

     // Récupérer toutes les transactions pour le gestionnaire
     public function index1()
     {
         // Vérifier si l'utilisateur est un gestionnaire (profil = 4)
         if (auth()->user()->profil != 4) {
             return response()->json(['message' => 'Accès refusé. Vous devez être un gestionnaire pour voir les transactions.'], 403);
         }
 
         // Si l'utilisateur est un gestionnaire, récupérer les transactions en attente
         $transactions = Transaction::with(['user', 'propriete'])
             ->where('statutTransaction', 'en attente') // Ne montrer que les transactions en attente de validation
             ->get();
             
         return response()->json($transactions);
     }
 
     // Mise à jour du statut d'une transaction
     public function updateStatut(Request $request, $id)
     {
         // Vérifier si l'utilisateur est un gestionnaire (profil = 4)
         if (auth()->user()->profil != 4) {
             return response()->json(['message' => 'Accès refusé. Vous devez être un gestionnaire pour mettre à jour les transactions.'], 403);
         }
 
         // Récupérer la transaction et mettre à jour son statut
         $transaction = Transaction::findOrFail($id);
         $transaction->statutTransaction = $request->input('statutTransaction'); // 'validée', 'rejetée'
         $transaction->user_id = auth()->user()->id; // ID du gestionnaire qui fait l'action
         $transaction->save();
 
         return response()->json(['message' => 'Statut mis à jour avec succès.']);
     }
}
