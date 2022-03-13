<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class RegisterUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        if($validator->fails()){
            return response()->json([
                'error'=>$validator->errors()
            ]);
        }

        $user = User::create([
            'name' => $request->email,
            'email' => $request->email,
            'password' => Hash::make($request->email),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $token = Auth::user()->createToken('register')->plainTextToken;
        return response()->json(['Token:' => $token]);
    }
}
