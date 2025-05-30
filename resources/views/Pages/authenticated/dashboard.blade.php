@extends('layout.authenticated-layout')

@section('title', 'Dashboard - BM Music')

@section('main-content')
    <div class="container-fluid py-4 px-5" style="height: 100vh;">
        <h2 class="text-light mb-4 fw-bold"><i class="bi bi-music-note-beamed"></i> Spotify Tracks</h2>
        <div id="music-player" class="fixed-bottom bg-dark text-light d-none px-4 py-2 shadow-lg" style="z-index: 1050;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3 me-2">
                    <img id="player-album-art" src="" alt="Album Art" width="50" height="50" class="rounded">
                    <div>
                        <div id="player-track-name" class="fw-bold"></div>
                        <div id="player-artist" class="text-muted small"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button id="pause-btn" class="btn btn-outline-light"><i class="bi bi-pause-fill"></i></button>
                    <button id="resume-btn" class="btn btn-outline-light"><i class="bi bi-play-fill"></i></button>
                </div>
                <div class="flex-grow-1 mx-3">
                    <div class="d-flex justify-content-between small text-light">
                        <span id="current-time">0:00</span>
                        <span id="total-time">0:00</span>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="track-container" class="row g-4"></div>
    </div>

    <script src="https://sdk.scdn.co/spotify-player.js"></script>

    <script>
        $(document).on('click', '.save-track-btn', function() {
            const button = $(this);

            const trackData = {
                spotify_track_id: button.data('id'),
                track_name: button.data('name'),
                artist: button.data('artist'),
                album_art: button.data('art')
            };

            $.ajax({
                url: '/spotify/save-track',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content')
                },
                data: trackData,
                success: function(response) {
                    Swal.fire({
                        title: 'Track Saved',
                        text: `${trackData.track_name} by ${trackData.artist} has been saved!`,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    button.prop('disabled', true).text('✅ Saved');
                },
                error: function(xhr) {
                    if (xhr.status === 409) {
                        Swal.fire({
                            title: 'Track Already Saved',
                            text: `${trackData.track_name} by ${trackData.artist} is already saved.`,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                        button.prop('disabled', true).text('✅ Already Saved');
                    } else {
                        alert('Something went wrong while saving the track.');
                    }
                }
            });
        });

        $('#search-btn').on('click', function() {
            const query = $('#search-query').val().trim();
            if (!query) return;

            $.ajax({
                url: '/spotify/search',
                method: 'GET',
                data: {
                    query
                },
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
                                    <button class="btn btn-outline-light w-100 mb-2 play-track-btn" data-uri="${track.uri}"><i class="bi bi-play-circle-fill"></i> Play</button>
                                    <button class="btn btn-outline-success w-100 save-track-btn"
                                        data-id="${track.id}"
                                        data-name="${track.name}"
                                        data-artist="${track.artists[0].name}"
                                        data-image="${track.album.images[0]?.url}">
                                        <i class="bi bi-bookmark-fill"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>`;
                        });
                        $('#track-container').html(html);
                    }
                },
                error: function() {
                    alert('Search failed.');
                }
            });
        });

        let player;
        let currentTrackDuration = 0;

        window.onSpotifyWebPlaybackSDKReady = () => {
            const token = '{{ $spotifyAccessToken }}';

            player = new Spotify.Player({
                name: 'BM Music Web Player',
                getOAuthToken: cb => {
                    cb(token);
                },
                volume: 0.7
            });

            player.addListener('player_state_changed', state => {
                if (!state) return;

                // Update player UI
                const track = state.track_window.current_track;
                $('#player-track-name').text(track.name);
                $('#player-artist').text(track.artists.map(a => a.name).join(', '));
                $('#player-album-art').attr('src', track.album.images[0]?.url || '');
                $('#music-player').removeClass('d-none');

                currentTrackDuration = state.duration;
            });

            player.addListener('ready', ({
                device_id
            }) => {
                window.spotifyDeviceId = device_id;
                console.log('Ready with Device ID', device_id);
            });

            player.connect();

            // Playback control buttons
            $('#pause-btn').click(() => player.pause());
            $('#resume-btn').click(() => player.resume());

            // Progress bar update
            setInterval(() => {
                if (!player || !currentTrackDuration) return;
                player.getCurrentState().then(state => {
                    if (state) {
                        const progressPercent = (state.position / currentTrackDuration) * 100;
                        $('#progress-bar').css('width', `${progressPercent}%`);
                    }
                });
            }, 1000);
        };

        $(document).ready(function() {
            function formatTime(ms) {
                const totalSeconds = Math.floor(ms / 1000);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                return `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
            }

            setInterval(() => {
                if (!player || !currentTrackDuration) return;
                player.getCurrentState().then(state => {
                    if (state) {
                        const progressPercent = (state.position / currentTrackDuration) * 100;
                        $('#progress-bar').css('width', `${progressPercent}%`);

                        // ⏱ Update time display
                        $('#current-time').text(formatTime(state.position));
                        $('#total-time').text(formatTime(currentTrackDuration));
                    }
                });
            }, 1000);

        })

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

        $(document).on('click', '.play-track-btn', function() {
            const trackUri = $(this).data('uri');
            const deviceId = window.spotifyDeviceId;

            if (!deviceId) {
                alert('Spotify Player not ready yet.');
                return;
            }

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
