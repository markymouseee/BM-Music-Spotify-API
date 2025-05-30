@extends('app')

@section('layout')
    <nav class="navbar navbar-expand-lg position-sticky top-0 shadow-sm"
        style="background-color: rgba(34, 40, 49, 0.85); backdrop-filter: blur(5px); z-index: 1030;">
        <div class="container">
            <a class="navbar-brand text-light fw-bold fs-4" href="/">
                <svg width="40px" height="40px" viewBox="0 0 1024 1024" class="icon" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M209.11128 258.0742c20.523667-7.860128 148.469083 191.699787 299.121535 191.699787s267.681023-206.110021 283.837953-195.193177-65.501066 208.730064-54.147548 279.034542c20.523667 145.849041 118.338593 102.181663 127.072069 288.204691 3.49339 80.347974-74.671215 205.236674-364.185928 201.743283S158.893796 906.098081 158.893796 822.256716c0-151.089126 80.784648-131.002132 105.675053-289.951386 10.480171-66.374414-72.92452-262.440938-55.457569-274.23113z"
                        fill="#fffff" />
                    <path
                        d="M660.195289 596.496375H566.310427V134.058849a43.667377 43.667377 0 0 0 40.610661-43.667378V43.667377a43.667377 43.667377 0 0 0-43.667377-43.667377H458.888679a43.667377 43.667377 0 0 0-43.667378 43.667377v48.470789a43.667377 43.667377 0 0 0 40.610661 43.667378v460.690831h-87.334754a20.960341 20.960341 0 0 0-20.960341 20.960341v229.690406a20.960341 20.960341 0 0 0 20.960341 20.960341h292.134754a20.960341 20.960341 0 0 0 22.270363-20.960341v-229.690406a20.960341 20.960341 0 0 0-22.707036-20.960341z"
                        fill="#D46882" />
                    <path d="M423.518103 653.70064l189.953092 0 0 67.684435-189.953092 0 0-67.684435Z" fill="#E6E9ED" />
                    <path d="M423.518103 755.882303l189.953092 0 0 67.684435-189.953092 0 0-67.684435Z" fill="#E6E9ED" />
                </svg>
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
                        <img src="{{ asset('images/img.jpg') }}" alt="admin" class="rounded-circle" height="40"
                            width="40">
                        <strong class="ms-2">Mark</strong>
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person-circle"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
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
        });


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
