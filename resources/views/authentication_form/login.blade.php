@extends('Layout.auth_form')

@section('title', 'Login')

@section('content')
    <div class="w-100">
        <div class="p-4 p-md-5">
            <h2 class="text-center fw-bold" style="margin-top: -25px;">Login</h2>
            <p class="text-center text-muted mb-4">Enter your credentials to log in</p>

            {{-- Display Error Message --}}
            @if ($errors->has('email'))
                <div class="alert alert-danger text-center">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control fw-semibold" id="email"
                            placeholder="Enter your email" required>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control fw-semibold" id="password"
                            placeholder="Enter your password" required>
                    </div>
                </div>

                <!-- Login Button -->
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-dark fw-bold">Login</button>
                </div>

                <!-- Links -->
                <div class="mt-3 text-center">
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-danger">
                        Forgot your password?
                    </a>
                </div>
                <div class="mt-2 text-center">
                    <a href="{{ route('register') }}" class="text-decoration-none">
                        Don't have an account? Register
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
