@extends('layout.authenticated-layout')

@section('title', 'Saved tracks - BM Music')

@section('main-content')
    <div class="container-fluid py-4 px-5" style="min-height: 100vh;">
        <h2 class="text-light mb-4 fw-bold"><i class="bi bi-bookmark"></i> Saved Tracks</h2>

        <div class="row" id="saved-track-container">
            @forelse($savedTracks as $track)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card text-bg-dark h-100 shadow-lg border-secondary rounded-4">
                        <img src="{{ $track->album_art }}" class="card-img-top rounded-top-4" alt="Album Art">
                        <div class="card-body">
                            <h5 class="card-title">{{ $track->track_name }}</h5>
                            <p class="card-text text-secondary">Artist: {{ $track->artist }}</p>

                            <div class="audio-player mb-2">
                                <audio id="audio-{{ $track->id }}" src="{{ $track->preview_url }}"></audio>
                                <div class="d-flex flex-column justify-content-between align-items-center">
                                    <button class="btn btn-outline-light w-100 mb-2 play-track-btn"
                                        data-uri="{{ $track->uri }}"><i class="bi bi-play-circle-fill"></i> Play
                                    </button>
                                </div>
                                <button class="btn btn-sm btn-danger w-100 delete-track-btn mt-2"
                                    data-id="{{ $track->id }}">
                                    <i class="bi bi-trash3"></i> Remove
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <p class="text-light">No saved tracks yet.</p>
            @endforelse
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let currentAudio = null;
            let currentButton = null;

            $('.play-btn').click(function() {
                const id = $(this).data('id');
                const audio = document.getElementById('audio-' + id);

                if (currentAudio && currentAudio !== audio) {
                    currentAudio.pause();
                    $(currentButton).html('<i class="bi bi-play-fill"></i>');
                }

                if (audio.paused) {
                    audio.play();
                    $(this).html('<i class="bi bi-pause-fill"></i>');
                    currentAudio = audio;
                    currentButton = this;
                } else {
                    audio.pause();
                    $(this).html('<i class="bi bi-play-fill"></i>');
                }

                audio.onended = () => {
                    $(this).html('<i class="bi bi-play-fill"></i>');
                };
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

        $(document).on('click', '.delete-track-btn', function() {
            const trackId = $(this).data('id');
            const card = $(this).closest('.col-md-4');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#343a40',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/spotify/saved-tracks/${trackId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        success: function(res) {
                            if (res.success) {
                                card.fadeOut(300, () => card.remove());
                                Swal.fire({
                                        title: 'Deleted!',
                                        text: 'Your track has been deleted.',
                                        icon: 'success',
                                        confirmButtonColor: '#343a40'
                                    })
                                    .then((isConfirmed) => {
                                        if (isConfirmed.isConfirmed) {
                                            window.location.reload();
                                        }
                                    })
                            } else {
                                alert('Failed to delete track.');
                            }
                        },
                        error: function() {
                            alert('Error deleting track.');
                        }
                    });
                }
            })
        });
    </script>
@endsection
