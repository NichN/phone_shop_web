@extends('Layout.auth_form')

@section('title', 'Reset Password')

@section('content')
<div class="w-100">
  <div class="p-4 p-md-5 mt-3"> <!-- Added mt-3 to move it up -->
    
    <!-- Back Link -->
    <div class="mb-4">
      <a href="{{ route('login') }}" onclick="login.back()" class="btn btn-secondary mb-4">← Back</a>
    </div>

    <!-- Header -->
    <h2 class="text-center mb-3 fw-bold">Reset Password</h2>
    <p class="text-center text-muted mb-4">Enter your new password</p>

    <!-- Form (frontend only) -->
    <form method="POST" action="#">
      <!-- Hidden Token Field -->
      <input type="hidden" name="token" value="{{ request()->query('token') }}">

      <!-- New Password Input -->
      <div class="mb-3">
        <label for="password" class="form-label fw-semibold">New Password</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control fw-semibold" placeholder="Enter new password" required>
        </div>
      </div>

      <!-- Confirm Password Input -->
      <div class="mb-3">
        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
        <div class="input-group">
          <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
          <input type="password" name="password_confirmation" id="password_confirmation" class="form-control fw-semibold" placeholder="Confirm your password" required>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary fw-bold">Reset Password</button>
      </div>
    </form>
  </div>
</div>
@endsection
