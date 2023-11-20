<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required'
        ]);

        $payment = new Payment; 
        $payment->amount = $request->amount; 
        $payment->date  = $request->date;
        $payment->transaction = $request->transaction;
        $payment->details = $request->details;
        $payment->save();

        return redirect()->back()->with('success', 'Payment store successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::find($id);
        $payment->delete();
        return redirect()->back();
    }
}
