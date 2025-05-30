@extends('layout.guest-layout')

@section('title', 'Login - BM Music')
@section('link', 'Sign up')

@section('main-content')
    <div class="col-11 col-sm-8 col-md-8 col-lg-6 col-xl-4">
        <div class="card shadow-lg border-0 rounded-4 animate-slide-up" style="background-color: rgba(255, 255, 255, 0.9);">
            <div class="card-body p-4">
                <h2 class="card-title text-center d-flex justify-content-center gap-2 align-items-center mb-3"
                    style="color: #222831;">
                    Welcome to <strong>BM Music</strong>
                    <svg width="40px" height="40px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M209.11128 258.0742c20.523667-7.860128 148.469083 191.699787 299.121535 191.699787s267.681023-206.110021 283.837953-195.193177-65.501066 208.730064-54.147548 279.034542c20.523667 145.849041 118.338593 102.181663 127.072069 288.204691 3.49339 80.347974-74.671215 205.236674-364.185928 201.743283S158.893796 906.098081 158.893796 822.256716c0-151.089126 80.784648-131.002132 105.675053-289.951386 10.480171-66.374414-72.92452-262.440938-55.457569-274.23113z"
                            fill="#434A54" />
                        <path
                            d="M660.195289 596.496375H566.310427V134.058849a43.667377 43.667377 0 0 0 40.610661-43.667378V43.667377a43.667377 43.667377 0 0 0-43.667377-43.667377H458.888679a43.667377 43.667377 0 0 0-43.667378 43.667377v48.470789a43.667377 43.667377 0 0 0 40.610661 43.667378v460.690831h-87.334754a20.960341 20.960341 0 0 0-20.960341 20.960341v229.690406a20.960341 20.960341 0 0 0 20.960341 20.960341h292.134754a20.960341 20.960341 0 0 0 22.270363-20.960341v-229.690406a20.960341 20.960341 0 0 0-22.707036-20.960341z"
                            fill="#D46882" />
                        <path
                            d="M423.518103 653.70064h189.953092v67.684435H423.518103zM423.518103 755.882303h189.953092v67.684435H423.518103z"
                            fill="#E6E9ED" />
                    </svg>
                </h2>
                <p class="card-text text-center text-muted mb-4">
                    Please sign in to continue<br>
                    <small class="text-success">This system uses the Spotify API for music integration.</small>
                </p>

                <form id="login-form">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="username_or_email" class="form-control rounded-3 border-0 shadow-sm"
                            id="username_or_email" placeholder="Username or email" required>
                        <label for="username_or_email" class="text-secondary">Username or email</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password_login" class="form-control rounded-3 border-0 shadow-sm"
                            id="password_login" placeholder="Password" required>
                        <label for="password_login" class="text-secondary">Password</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark rounded-pill btn-lg text-white">
                            <svg width="20px" height="20px" viewBox="0 0 32 32" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                    sketch:type="MSPage">
                                    <g id="Icon-Set" sketch:type="MSLayerGroup"
                                        transform="translate(-308.000000, -1087.000000)" fill="currentColor">
                                        <path
                                            d="M324,1117 C316.268,1117 310,1110.73 310,1103 C310,1095.27 316.268,1089 324,1089 C331.732,1089 338,1095.27 338,1103 C338,1110.73 331.732,1117 324,1117 L324,1117 Z M324,1087 C315.163,1087 308,1094.16 308,1103 C308,1111.84 315.163,1119 324,1119 C332.837,1119 340,1111.84 340,1103 C340,1094.16 332.837,1087 324,1087 L324,1087 Z M330.535,1102.12 L324.879,1096.46 C324.488,1096.07 323.855,1096.07 323.465,1096.46 C323.074,1096.86 323.074,1097.49 323.465,1097.88 L327.586,1102 L317,1102 C316.447,1102 316,1102.45 316,1103 C316,1103.55 316.447,1104 317,1104 L327.586,1104 L323.465,1108.12 C323.074,1108.51 323.074,1109.15 323.465,1109.54 C323.855,1109.93 324.488,1109.93 324.879,1109.54 L330.535,1103.88 C330.775,1103.64 330.85,1103.31 330.795,1103 C330.85,1102.69 330.775,1102.36 330.535,1102.12 L330.535,1102.12 Z"
                                            id="arrow-right-circle" sketch:type="MSShapeGroup">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                            Sign in
                        </button>
                    </div>
                </form>

                <div class="d-flex align-items-center mt-3">
                    <span class="flex-fill border-top"></span>
                    <span class="px-3 text-muted">OR</span>
                    <span class="flex-fill border-top"></span>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ url('/spotify/login') }}" class="btn btn-outline-success rounded-pill w-100">
                        <svg width="20px" height="20px" viewBox="0 0 48 48" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Color-" transform="translate(-200.000000, -460.000000)" fill="#00DA5A">
                                    <path
                                        d="M238.16,481.36 C230.48,476.8 217.64,476.32 210.32,478.6 C209.12,478.96 207.92,478.24 207.56,477.16 C207.2,475.96 207.92,474.76 209,474.4 C217.52,471.88 231.56,472.36 240.44,477.64 C241.52,478.24 241.88,479.68 241.28,480.76 C240.68,481.6 239.24,481.96 238.16,481.36 M237.92,488.08 C237.32,488.92 236.24,489.28 235.4,488.68 C228.92,484.72 219.08,483.52 211.52,485.92 C210.56,486.16 209.48,485.68 209.24,484.72 C209,483.76 209.48,482.68 210.44,482.44 C219.2,479.8 230,481.12 237.44,485.68 C238.16,486.04 238.52,487.24 237.92,488.08 M235.04,494.68 C234.56,495.4 233.72,495.64 233,495.16 C227.36,491.68 220.28,490.96 211.88,492.88 C211.04,493.12 210.32,492.52 210.08,491.8 C209.84,490.96 210.44,490.24 211.16,490 C220.28,487.96 228.2,488.8 234.44,492.64 C235.28,493 235.4,493.96 235.04,494.68 M224,460 C210.8,460 200,470.8 200,484 C200,497.2 210.8,508 224,508 C237.2,508 248,497.2 248,484 C248,470.8 237.32,460 224,460"
                                        id="Spotify">

                                    </path>
                                </g>
                            </g>
                        </svg>
                        Sign-in with Spotify
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(() => {
            handleLogin();
        });

        function handleLogin() {
            $('#login-form').on('submit', async function(e) {
                e.preventDefault();

                const formData = {
                    username_or_email: $('#username_or_email').val(),
                    password_login: $('#password_login').val()
                }

                try {
                    const res = await fetch('/api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': $('input[name="_token"]').val()
                        },

                        body: JSON.stringify(formData)
                    })


                    const data = await res.json();

                    if (res.ok) {
                        window.location.href = data.redirect;
                    } else {
                        if (data.errors)[
                            Object.keys(data.errors).forEach((key) => {
                                const inputs = $(`input[name="${key}"]`);

                                if (inputs.length) {
                                    inputs.addClass('is-invalid');
                                    inputs.next().text(data.errors[key][0]);
                                }
                            })
                        ]
                    }
                } catch (error) {
                    console.error("Error: ", error);
                }
            })
        }
    </script>
@endsection
