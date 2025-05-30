@extends('layout.authenticated-layout')

@section('title', 'Dashboard - BM Music')

@section('main-content')
    <div class="container-fluid py-4 px-5 overflow-y-auto" style="height: 100vh;">
        <h2 class="text-light mb-4"><i class="bi bi-music-note-beamed"></i> Your Top Tracks</h2>

        <div id="track-container" class="row g-4"></div>

        <div class="player-controls text-center mt-5">
            <button id="play" class="btn btn-success me-2 px-4"><i class="bi bi-play-fill"></i> Play</button>
            <button id="pause" class="btn btn-danger px-4"><i class="bi bi-pause-fill"></i> Pause</button>
        </div>
    </div>

    {{-- Spotify Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://sdk.scdn.co/spotify-player.js"></script>

    <script>
        let player;
        window.onSpotifyWebPlaybackSDKReady = () => {
            const token = '{{ $spotifyAccessToken }}';

            player = new Spotify.Player({
                name: 'BM Music Web Player',
                getOAuthToken: cb => cb(token)
            });

            player.addListener('initialization_error', ({
                message
            }) => console.error(message));
            player.addListener('authentication_error', ({
                message
            }) => console.error(message));
            player.addListener('account_error', ({
                message
            }) => {
                console.error(message);
                alert('Spotify Premium required!');
            });
            player.addListener('playback_error', ({
                message
            }) => console.error(message));

            player.addListener('ready', ({
                device_id
            }) => {
                console.log('Ready with Device ID', device_id);
                window.spotifyDeviceId = device_id;
            });

            player.connect();
        };

        $(document).ready(function() {
            $.ajax({
                url: '/spotify/top-tracks-ajax',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        let html = '';
                        response.tracks.forEach(track => {
                            html += `
                                <div class="col-md-4 col-lg-3">
                                    <div class="card text-bg-dark h-100 shadow-lg border-secondary rounded-4">
                                        <img src="${track.album.images[0]?.url}" class="card-img-top rounded-top-4" alt="Album Art">
                                        <div class="card-body">
                                            <h5 class="card-title">${track.name}</h5>
                                            <p class="card-text text-secondary">Artist: ${track.artists[0].name}</p>
                                            <button class="btn btn-outline-light w-100 play-track-btn" data-uri="${track.uri}">
                                                <i class="bi bi-play-circle-fill"></i> Play This Track
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        $('#track-container').html(html);
                    } else {
                        $('#track-container').html('<p class="text-light">Could not fetch tracks.</p>');
                    }
                },
                error: function() {
                    $('#track-container').html('<p class="text-light">Error loading tracks.</p>');
                }
            });
        });

        $('#play').click(() => player.resume().then(() => console.log('Playback resumed')));
        $('#pause').click(() => player.pause().then(() => console.log('Playback paused')));

        $(document).on('click', '.play-track-btn', function() {
            const trackUri = $(this).data('uri');
            const deviceId = window.spotifyDeviceId;

            if (!deviceId) return alert('Spotify Player is not ready yet.');

            $.ajax({
                url: `https://api.spotify.com/v1/me/player/play?device_id=${deviceId}`,
                type: 'PUT',
                data: JSON.stringify({
                    uris: [trackUri]
                }),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer {{ $spotifyAccessToken }}');
                    xhr.setRequestHeader('Content-Type', 'application/json');
                },
                success: function() {
                    console.log('Track playing');
                },
                error: function(xhr) {
                    alert('Error playing track: ' + xhr.responseText);
                }
            });
        });
    </script>
@endsection
