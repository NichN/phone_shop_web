@extends('Layout.auth_form')

@section('title', 'Registration')

@section('content')
  <div class=" mt-5">
    <div class="row justify-content-center">
      <div class="p-3 ">
          <h2 class="text-center">Registration</h2>
          <p class="text-center">Fill in the details to register</p>
          <form>
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold">Full Name</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" id="name" placeholder="Enter your full name" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
              <input type="email" class="form-control fw-semibold" id="email" placeholder="Enter your email" required>
            </div> 
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                <input type="password" class="form-control" id="password" placeholder="Create a password" required>
            </div>
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm your password" required>
            </div>    
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Register</button>
            </div>

            <!-- Redirect to Login -->
            {{-- <div class="mt-3 text-center">
              <a href="{{ route('login') }}" class="text-decoration-none">Already have an account? Login</a>
            </div> --}}
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
