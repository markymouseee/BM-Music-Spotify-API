@extends('layout.guest-layout')

@section('title', 'Login - BM Music')
@section('link', 'Sign up')

@section('main-content')
    <div class="col-11 col-sm-8 col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4" style="background-color: rgba(255, 255, 255, 0.9);">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-3" style="color: #222831;">Welcome to <strong>BM Music</strong></h2>
                <p class="card-text text-center text-muted mb-4">Please log in to continue</p>

                <form>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="username"
                            placeholder="Username or email">
                        <label for="username" class="text-secondary">Username or email</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control rounded-3 border-0 shadow-sm" id="password"
                            placeholder="Password">
                        <label for="password" class="text-secondary">Password</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn rounded-pill btn-lg text-white"
                            style="background-color: #222831;">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
