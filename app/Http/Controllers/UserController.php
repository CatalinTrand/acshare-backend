<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($user->save())
            return response()->json(['message' => 'User created successfully'], 201);
        else
            return response()->json(['message' => 'There was an error.'], 500);
    }

    public function login(Request $request){
        $user = User::where('email',$request->email)->get()->first();

        if(Hash::check($request->password,$user->password)) {
            $user->remember_token = Str::random(20);
            $user->save();
            return response()->json(['message' => $user->remember_token], 200);
        } else
            return response()->json(['message' => 'Invalid credidentials.'], 500);
    }
}
