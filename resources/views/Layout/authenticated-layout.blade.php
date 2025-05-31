@extends('app')

@section('layout')
    <nav class="navbar navbar-expand-lg position-sticky top-0 shadow-sm"
        style="background-color: rgba(34, 40, 49, 0.85); backdrop-filter: blur(5px); z-index: 1030;">
        <div class="container">
            <a class="navbar-brand text-light fw-bold fs-4" href="/">
               <img src="{{ asset('images/logo.png') }}" alt="logo" class="d-inline-block align-text-top me-2" height="40" width="40">
                BM MUSIC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

                <div class="d-flex ms-auto" role="search">
                    <input class="form-control me-2" type="search" id="search-query" placeholder="Search song"
                        aria-label="Search">
                    <button class="btn btn-outline-light" id="search-btn" type="submit">Search</button>
                </div>
                <div class="dropdown cursor-pointer ms-2">
                    <a class="nav-link text-light d-flex align-items-center dropdown-toggle " data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="@yield('profile')" alt="admin" class="rounded-circle" height="40" width="40">

                        <strong class="ms-2">@yield('usersname')</strong>
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard.profile') }}">
                                <i class="bi bi-person-circle"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('spotify.saved.tracks') }}">
                                <i class="bi bi-bookmark"></i>
                                Saved tracks
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <button class="dropdown-item text-danger" id="btn-logout">
                                <i class="bi bi-box-arrow-right"></i>
                                Log out
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>

    <main class="flex-fill d-flex justify-content-center align-items-center py-5 overflow-auto"
        style="background-image:  linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('images/guestbackground.jpeg') }}); background-size: cover; background-repeat: no-repeat; background-position: center;">
        @yield('main-content')
    </main>

    <footer class="text-center text-light py-3"
        style="background-color: rgba(34, 40, 49, 0.85); backdrop-filter: blur(5px);">
        <div>
            <small>&copy; <span id="year"></span> BM Music. All rights reserved.</small>
        </div>
    </footer>
    <script>
        $(document).ready(function() {
            handleLogout();
            searchSongs();
        });

        function searchSongs() {
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
        }

        function handleLogout() {
            $("#btn-logout").on('click', async function() {
                Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        text: 'You will be logged out',
                        showCancelButton: true,
                        confirmButtonColor: '#adb5bd',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, log out!'
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/api/logout',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Logged out successfully',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.href = response.redirect;
                                    });
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Failed to log out. Please try again.',
                                        icon: 'error'
                                    });
                                }
                            })
                        }
                    })
            })
        }
    </script>
@endsection
