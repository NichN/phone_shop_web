@extends('Layout.auth_form')

@section('title', 'Login')

@section('content')
<div class="mt-5">
  <div class="row justify-content-center">
    <div class="p-5">
      <h2 class="text-center">Login</h2>
      <p class="text-center">Enter your credentials to log in</p>
      <form>
        <div class="mb-3">
          <label for="email" class="form-label fw-semibold">Email Address</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
            <input type="email" class="form-control fw-semibold" id="email" placeholder="Enter your email" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label fw-semibold">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
          </div>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
        <div class="mt-3 text-center">
          <a href="{{route('verifyemail')}}" class="text-decoration-none text-danger">Forgot your password?</a>
        </div>
        <div class="mt-2 text-center">
          <a href="{{route('register')}}" class="text-decoration-none">Don't have an account? Register</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
