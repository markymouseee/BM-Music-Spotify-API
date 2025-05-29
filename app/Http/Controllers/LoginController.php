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
        return redirect()->intended(route('login.index'));
    }

    public function login(Request $request)
    {
        $validation = $request->validate([
            'username_or_email' => 'required',
            'password' => 'required',
        ], [
            'username_or_email.required' => 'Username or email is required.',
            'password.required' => 'Password is required.',
        ]);

        $user = User::where('username', $validation['username_or_email'])
            ->orWhere('email', $validation['username_or_email'])
            ->firstOrFail();

        if ($user) {
            if (Hash::check($validation['password'], $user->password)) {
                Auth::login($user);
                return response()->json([
                    'redirect' => route('')
                ]);
            } else {
                return response()->json([
                    'message' => 'Incorrect password.'
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Username or email not found.'
            ]);
        }
    }
}