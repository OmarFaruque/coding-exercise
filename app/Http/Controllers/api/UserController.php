<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;

class UserController extends Controller
{
    /**
     * Login user
     */
    public function login(Request $request){
        
        $input = $request->all();

        $validation = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 422);
        }

        if(Auth::attempt(['email' => $input['email'], 'password' => $input['password']])){
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->accessToken;
            return response()->json( ['token' => $token] );
        }

        
    }
}
