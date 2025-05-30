<?php

namespace App\Http\Controllers;

use App\Models\SavedTrack;
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

    public function search(Request $request)
    {
        $query = $request->query('query');
        $token = Auth::user()->spotify_access_token;

        $response = Http::withToken($token)->get('https://api.spotify.com/v1/search', [
            'q' => $query,
            'type' => 'track',
            'limit' => 10,
        ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'tracks' => $response->json()['tracks']['items']
            ]);
        }

        return response()->json(['success' => false], 500);
    }

    public function saveTrack(Request $request)
    {
        $request->validate([
            'spotify_track_id' => 'required|string',
            'track_name' => 'required|string',
            'artist' => 'required|string',
            'album_art' => 'nullable|string',
        ]);

        $user = Auth::user();


        $exists = SavedTrack::where('user_id', $user->id)
            ->where('spotify_track_id', $request->spotify_track_id)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Track already saved.'], 409);
        }

        SavedTrack::create([
            'user_id' => $user->id,
            'spotify_track_id' => $request->spotify_track_id,
            'track_name' => $request->track_name,
            'artist' => $request->artist,
            'album_art' => $request->album_art,
        ]);

        return response()->json(['success' => true, 'message' => 'Track saved!']);
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
                'username' => $spotifyUser['id'],
                'password' => bcrypt(str(16)),
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

        if (!$user || !$user->spotify_access_token) {
            return response()->json([
                'success' => false,
                'message' => 'Spotify access token is missing.',
            ], 400);
        }

        $response = Http::withToken($user->spotify_access_token)
            ->get('https://api.spotify.com/v1/me/top/tracks', [
                'limit' => 12,
            ]);

        if (!$response->successful()) {
            Log::error('Spotify API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'tracks' => $response->json()['items'],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Spotify API error: ' . $response->status(),
                'error' => json_decode($response->body(), true),
            ], 400);
        }
    }

    public function playTrack(Request $request)
    {
        $user = Auth::user();
        $trackUri = $request->input('track_uri');

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
