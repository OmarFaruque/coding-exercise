<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TransactionsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        
        return view('newrestraction')->with('users', $users);;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required',
            'payer' => 'required',
            'dueon' => 'required',
            'vat' => 'required',
            'vat_included' => 'required',
        ]);

        $transaction = new Transactions;
        $transaction->amount = $request->amount;
        $transaction->payer = $request->payer;
        $transaction->dueon = $request->dueon;
        $transaction->vat = $request->vat;
        $transaction->vat_included = $request->vat_included;
        $transaction->save();

        return redirect()->route('home')->with('status', 'Transactions store successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transactions::leftJoin('users', 'transactions.payer', '=', 'users.id')->select('transactions.*', 'users.name')->where('transactions.id', $id)->first();
        $transaction->withvat = $transaction->vat_included ? (int)$transaction->amount + (((int)$transaction->amount * (int)$transaction->vat) / 100) : $transaction->amount;
        $transaction->status = 'paid';

        $paymentTotal = Payment::selectRaw('sum(amount) as total')->where('transaction', $id)->first();
   
        

        if(strtotime(date('Y-m-d')) <= strtotime($transaction->dueon) && $paymentTotal->total < $transaction->withvat ){
            $transaction->status = 'outstanding';
        }
        if(strtotime(date('Y-m-d')) >= strtotime($transaction->dueon) && $paymentTotal->total < $transaction->withvat){
            $transaction->status = 'overdue';
        }


        $payments = Payment::where('transaction', $id)->get();
        return view('transactions/viewtransaction')->with(['transactions' => $transaction, 'payments' => $payments]);
    }


    /**
     * View Montly report
     */
    public function viewreport(Request $request){
        $start_date = $request->start;
        $end_date = $request->end;
        $reports = Transactions::leftJoin('payments', 'payments.transaction', '=', 'transactions.id')
                    ->selectRaw('sum(payments.amount) as paid, YEAR(transactions.dueon) as year, MONTH(transactions.dueon) as month')
                    ->groupBy('year', 'month')
                    ->get()
                    ->map(function($v){
                        $outstanding = Transactions::leftJoin('payments', 'payments.transaction', '=', 'transactions.id' )
                                        ->selectRaw('sum(transactions.amount) - sum(payments.amount) as outstanding')->whereDate('transactions.dueon', '>', now())->first();

                        $overdue = Transactions::leftJoin('payments', 'payments.transaction', '=', 'transactions.id' )
                                        ->selectRaw('sum(transactions.amount) - sum(payments.amount) as overdue')->whereDate('transactions.dueon', '<', now())->first();
                        
                        $v->outstanding = $outstanding->outstanding;
                        $v->overdue = $overdue->overdue;
                        return $v;
                    });

        return view('transactions/report')->with(['reports' => $reports]);
    }

        /**
     * Display a reports.
     */
    public function reports()
    {
        return view('transactions/report');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transactions::find($id);
        $transaction->delete();
        return redirect()->back();
    }
}
