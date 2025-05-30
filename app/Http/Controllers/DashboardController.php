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
}
