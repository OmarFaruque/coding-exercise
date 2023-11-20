<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Transactions;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactions = Transactions::leftJoin('users', 'transactions.payer', '=', 'users.id')->select('transactions.*', 'users.name');

        if(auth()->user()->role == 'customer')
            $transactions->where('payer', auth()->user()->id);
        
        $transactions = $transactions->get();
        
        return view('dashboard')->with('transactions', $transactions);
    }
}
