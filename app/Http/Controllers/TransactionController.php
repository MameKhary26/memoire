<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        // Récupérer les transactions de l'utilisateur connecté
        $transactions = Transaction::where('user_id', Auth::id())->get();

        return response()->json($transactions);
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
}
