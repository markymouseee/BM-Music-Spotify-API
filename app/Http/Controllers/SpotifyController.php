<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class SpotifyController extends Controller
{
    public function redirectToSpotify()
    {
        $query = http_build_query([
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
            'scope' => 'streaming user-read-email user-read-private user-modify-playback-state user-read-playback-state user-top-read'
        ]);

        return redirect("https://accounts.spotify.com/authorize?$query");
    }

    public function handleSpotifyCallback(Request $request)
    {
        $code = $request->get('code');

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        ]);


        $accessToken = $response->json()['access_token'];
        $refreshToken = $response->json()['refresh_token'];


        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://api.spotify.com/v1/me');

        $spotifyUser = $userResponse->json();

        $user = \App\Models\User::updateOrCreate(
            ['spotify_id' => $spotifyUser['id']],
            [
                'name' => $spotifyUser['display_name'] ?? 'Spotify User',
                'email' => $spotifyUser['email'],
                'username' => $spotifyUser['id'], // Using Spotify ID as username
                'password' => bcrypt(str(16)), // Generate a random password
                'spotify_access_token' => $accessToken,
                'spotify_refresh_token' => $refreshToken,
                'spotify_token_expires_at' => now()->addSeconds($response->json()['expires_in']),
            ]
        );

        Auth::login($user);

        return redirect('/');
    }


    public function fetchTopTracksAjax()
    {
        $user = Auth::user();

        // Check if access token exists
        if (!$user || !$user->spotify_access_token) {
            return response()->json([
                'success' => false,
                'message' => 'Spotify access token is missing.',
            ], 400);
        }

        // Make the request to Spotify API
        $response = Http::withToken($user->spotify_access_token)
            ->get('https://api.spotify.com/v1/me/top/tracks', [
                'limit' => 12,
            ]);

        // Debug logging
        if (!$response->successful()) {
            Log::error('Spotify API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        // Handle response
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'tracks' => $response->json()['items'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Spotify API error: ' . $response->status(),
                'error' => json_decode($response->body(), true), // optional: show Spotify's error
            ], 400);
        }
    }

    public function playTrack(Request $request)
    {
        $user = Auth::user();
        $trackUri = $request->input('track_uri'); // e.g. spotify:track:TRACK_ID

        $response = Http::withToken($user->spotify_access_token)
            ->put('https://api.spotify.com/v1/me/player/play', [
                'uris' => [$trackUri]
            ]);

        if ($response->successful()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => $response->json()], 400);
        }
    }
}
