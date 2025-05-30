<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index'])
    ->name('login.index');

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard.index');

Route::get('/auth/spotify', [SpotifyController::class, 'redirectToSpotify'])
    ->name('spotify.redirect');

Route::get('/auth/spotify/callback', [SpotifyController::class, 'handleSpotifyCallback']);

Route::get('/spotify/top-tracks-ajax', [SpotifyController::class, 'fetchTopTracksAjax'])
    ->name('spotify.top-tracks.ajax');

Route::get('/spotify/search', [SpotifyController::class, 'search'])
    ->name('spotify.search');

Route::post('/spotify/save-track', [SpotifyController::class, 'saveTrack'])
    ->name('spotify.save.track');

Route::get('/spotify/saved-tracks',  [SpotifyController::class, 'showSavedTracks'])
    ->name('spotify.saved.tracks');

Route::delete('/spotify/saved-tracks/{id}', [SpotifyController::class, 'deleteSavedTrack'])
    ->name('spotify.saved.track.delete');
