<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::check()){
            return view('pages.authenticated.dashboard');
        }else{
            return redirect()->intended(route('login.index'));
        }
    }

    public function logout()
    {
       if(!Auth::check()){
            return redirect()->intended(route('login.index'));
        }

        Auth::logout();

        return response()->json([
            'redirect' => route('login.index')
        ]);
    }
}
