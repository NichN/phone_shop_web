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

            <!-- Form (frontend only) -->
            <form method="POST" action="#">
                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" id="email" class="form-control fw-semibold" placeholder="Enter your email"
                            required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">Send Reset Link</button>
                </div>
            </form>
        </div>
    </div>

    {{-- <div class="mt-5">
  <div class="row justify-content-center">
    <div class="mt-0 text-style">
        <span><i class="fa-solid fa-arrow-left"></i></span>
        <a href="{{route('login')}}" class="text-decoration-none text-muted">Back</a>
      </div>
    <div class="p-5">
      <h2 class="text-center">Forgot Password</h2>
      <p class="text-center">Enter your email to reset your password</p>
      <form>
        <div class="mb-3">
          <label for="email" class="form-label fw-semibold">Email Address</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
            <input type="email" class="form-control fw-semibold" id="email" placeholder="Enter your email" required>
          </div>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </div>
      </form>
    </div>
  </div>
</div> --}}
@endsection
