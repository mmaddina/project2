<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function signin(){
        return view('auth.registr');
    }

    function registr(Request $request){
        $request->validate([
            'name' => 'required',
            'email' =>'required|email|unique:App\Models\User',
            'password' =>'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' =>$request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->createToken('MyAppToken');
        return redirect()->route('login');
    }

    function signup(){
        return view('auth.signup');
    }

    function login(Request $request){
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6',
        ]);
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended('/article');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

    }

    function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('article.index');
    }
}
