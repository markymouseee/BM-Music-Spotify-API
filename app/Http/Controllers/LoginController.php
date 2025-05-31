<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('pages.auth.login');
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function login(Request $request)
    {
        $validation = $request->validate([
            'username_or_email' => 'required',
            'pass' => 'required',
        ], [
            'username_or_email.required' => 'Username or email is required.',
            'pass.required' => 'Password is required.',
        ]);

        $checkuser = User::whereNull('spotify_id')->first();

        if ($checkuser) {
           return redirect('/login')->with('error', 'Please link your Spotify account first.');
        }

        $user = User::where('username', $validation['username_or_email'])
            ->orWhere('email', $validation['username_or_email'])
            ->first();

        if ($user) {
            if (Hash::check($validation['pass'], $user->password)) {
                Auth::login($user);
                return response()->json([
                    'message' => 'Login successfully.',
                    'redirect' => route('dashboard.index')
                ]);
            } else {
                return response()->json([
                    'message' => 'Password is incorrect.'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Username or email not found.'
            ]);
        }
    }
}
