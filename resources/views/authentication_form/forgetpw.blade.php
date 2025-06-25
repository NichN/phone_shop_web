@extends('Layout.auth_form')

@section('title', 'Forgot Password')

@section('content')

    <div class="w-100">
        <div class="p-4 p-md-5">
            <!-- Back Link -->
            <div class="mb-4">
                <a href="{{ route('login') }}" onclick="login.back()" class="btn btn-secondary mb-4">← Back</a>
            </div>

            <!-- Header -->
            <h2 class="text-center mb-3 fw-bold">Forgot Password</h2>
            <p class="text-center text-muted mb-4">Enter your email to reset your password</p>

            <!-- Success Message -->
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" id="email" name="email" class="form-control fw-semibold @error('email') is-invalid @enderror" 
                               placeholder="Enter your email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">Send Reset Link</button>
                </div>
            </form>
        </div>
    </div>
@endsection
