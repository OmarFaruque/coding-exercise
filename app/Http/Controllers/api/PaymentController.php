<?php

namespace App\Http\Controllers\api;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validation = Validator::make($input, [
            'amount' => 'required'
        ]);

        if($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 422);
        }

        $payment = new Payment; 
        $payment->amount = $request->amount; 
        $payment->date  = $request->date;
        $payment->transaction = $request->transaction;
        $payment->details = $request->details;
        $payment->save();

        return response()->json(['success' => 'Payment store successfully.']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::find($id);
        $payment->delete();
        return response()->json(['success' => 'Payment destroy successfully.']);
    }
}
