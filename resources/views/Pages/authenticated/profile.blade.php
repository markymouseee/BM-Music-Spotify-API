@extends('layout.authenticated-layout')

@section('title', 'Profile - BM Music')

@section('profile', $user->profile_image)
@section('usersname', $user->name)

@section('main-content')
    <div class="container col-md-6 vh-100">
        <div class="card shadow-lg border-0 rounded-4 animate-slide-up">
            <div class="card-body p-4">
                <h2 class="card-title">
                    Profile
                </h2>
                <div>
                    <img src="{{ $user->profile_image }}" alt="" class="rounded-circle mb-3" width="50"
                        height="50">
                </div>
                <input type="hidden" value="{{ $user->id }}" name="user_id" id="user_id">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}">
                </div>

                <div>
                    <button type="button" id="btn-update" class="btn btn-outline-success">Update profile</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#btn-update").click(async function() {

                const formData = {
                    name: $("#name").val(),
                    email: $("#email").val(),
                    username: $("#username").val(),
                }

                const id = $("#user_id").val();

                try {
                    const response = await fetch(`/profile/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify(formData)
                    });

                    const data = await response.json();

                    if(response.ok){
                        Swal.fire({
                            icon: 'success',
                            title: 'Profile Updated',
                            text: data.message,
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: data.message,
                        });
                    }
                       
                } catch (error) {
                    console.error('Error updating profile:', error);
                }
            });
            
        })
    </script>
@endsection
