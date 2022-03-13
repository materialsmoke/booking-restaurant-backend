<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors]);
        }

        $credentials = ['email' => $request->email, 'password' => $request->email];

        if(Auth::attempt($credentials)){
            return response()->json(['Token' => Auth::user()->createToken('login')->plainTextToken]);
        }

        return response()->json(['status' => 'error', 'message'=> '{email} or {password} is wrong. check your inputs.']);
    }
}
