<?php

namespace App\Http\Controllers\api;

use App\Models\Payment;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class TransactionsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return response()->json(['users' => $users]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validation = Validator::make($input, [
            'amount' => 'required',
            'payer' => 'required',
            'dueon' => 'required',
            'vat' => 'required',
            'vat_included' => 'required'
        ]);

        if($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $transaction = new Transactions;
        $transaction->amount = $request->amount;
        $transaction->payer = $request->payer;
        $transaction->dueon = $request->dueon;
        $transaction->vat = $request->vat;
        $transaction->vat_included = $request->vat_included;
        $transaction->save();

        return response()->json(['success' => 'Transaction store successfully.']);
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
        return response()->json(['transactions' => $transaction, 'payments' => $payments]);
        
    }



    /**
    * View Montly report
    */
    public function viewreport(Request $request){

        $input = $request->all();

        $validation = Validator::make($input, [
            'start' => 'required',
            'end' => 'required'
        ]);

        if($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 422);
        }

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

        return  response()->json(['reports' => $reports]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transactions::find($id);
        $transaction->delete();
        return response()->json(['success' => 'Transaction destroy successfully.']);
    }
}
