<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\Events\Registered;

class RegisterOrLoginUserService
{
    private $user;

    public function __construct(private $email)
    {

    }
    
    public function getUser()
    {
        // dd($this->user);
        $user = User::where('email', $this->email)->first();

        if(!$user){
            //we will register the user
            $user = User::create([
                'name' => $this->email,
                'email' => $this->email,
                'password' => Hash::make($this->email),
            ]);
    
            event(new Registered($user));
        }

        return $user;
    }
}