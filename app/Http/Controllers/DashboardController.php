<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return view('pages.authenticated.dashboard', [
                'user' => $user,
                'spotifyAccessToken' => $user->spotify_access_token
            ]);
        } else {
            return redirect()->route('login.index');
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();

            return response()->json([
                'message' => 'Logged out successfully.',
                'redirect' => route('login.index')
            ]);
        }

        return response()->json([
            'message' => 'No user is currently logged in.',
            'redirect' => route('login.index')
        ], 200);
    }

    public function profile()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return view('pages.authenticated.profile', [
                'user' => $user,
                'spotifyAccessToken' => $user->spotify_access_token
            ]);
        } else {
            return redirect()->route('login.index');
        }
    }

    public function update(Request $request, $id){
        $user = Auth::user();

        if ($user->id !== (int)$id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();
        return response()->json(['message' => 'Profile updated successfully.']);
    }
}
