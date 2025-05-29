<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed:password_confirmation',
            'password_confirmation' => 'required|min:8|same:password',
        ], [
            'name.required' => 'Fullname is required.',
            'password_confirmation.required' => 'Re-type password is required.',
        ]);

        $passwordHashed = bcrypt($validation['password']);

        $user = User::create([
            'name' => $validation['name'],
            'username' => $validation['username'],
            'email' => $validation['email'],
            'password' => $passwordHashed,
        ]);

        if (!$user) {
            return response()->json([
                'message' => 'Failed to create account, please try again later.'
            ]);
        }

        return response()->json([
            'message' => 'Account created successfully.',
            'redirect' => route('login.index')
        ]);
    }
}