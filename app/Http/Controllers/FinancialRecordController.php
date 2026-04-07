<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialRecordController extends Controller
{
    public function index(Request $request)
    {
        $dentists = Dentist::orderBy('name')->get();
        
        $selectedDentistId = $request->get('dentist_id');
        $selectedDentist = null;
        $transactions = collect();
        $balance = 0;

        if ($selectedDentistId) {
            $selectedDentist = Dentist::find($selectedDentistId);
            if ($selectedDentist) {
                $transactions = FinancialRecord::where('dentist_id', $selectedDentistId)
                    ->orderBy('transaction_date', 'desc')
                    ->get();
                
                $totalCredits = $transactions->where('type', 'credit')->sum('amount');
                $totalDebits = $transactions->where('type', 'debit')->sum('amount');
                $balance = $totalDebits - $totalCredits;
            }
        }

        $dentistsWithBalance = Dentist::withSum(['financialRecords as total_credits' => function ($query) {
                $query->where('type', 'credit');
            }], 'amount')
            ->withSum(['financialRecords as total_debits' => function ($query) {
                $query->where('type', 'debit');
            }], 'amount')
            ->orderBy('name')
            ->get()
            ->map(function ($dentist) {
                $dentist->balance = ($dentist->total_debits ?? 0) - ($dentist->total_credits ?? 0);
                return $dentist;
            });

        return view('financial.index', compact('dentists', 'selectedDentist', 'transactions', 'balance', 'dentistsWithBalance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
            'patient_name' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        FinancialRecord::create($validated);

        return redirect()->route('financial.index', ['dentist_id' => $request->dentist_id])->with('success', 'Lançamento financeiro registrado com sucesso!');
    }

    public function extrato(Dentist $dentist)
    {
        $transactions = FinancialRecord::where('dentist_id', $dentist->id)
            ->orderBy('transaction_date', 'asc')
            ->get();
        
        $totalCredits = $transactions->where('type', 'credit')->sum('amount');
        $totalDebits = $transactions->where('type', 'debit')->sum('amount');
        $balance = $totalDebits - $totalCredits;

        return view('financial.extrato', compact('dentist', 'transactions', 'balance', 'totalCredits', 'totalDebits'));
    }
}
