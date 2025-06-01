@extends('Layout.auth_form')

@section('title', 'Registration')

@section('content')
    <div class="w-100">
        <div class="p-4 p-md-5">
            <h2 class="text-center mb-3 fw-bold">Registration</h2>
            <p class="text-center text-muted mb-4">Fill in the details to register</p>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Enter your full name" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control fw-semibold"
                            placeholder="Enter your email" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Create a password" required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                            placeholder="Confirm your password" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">Register</button>
                </div>

                <!-- Redirect to Login -->
                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">Already have an account? Login</a>
                </div>
            </form>
        </div>
    </div>
    
@endsection
