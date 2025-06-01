@extends('Layout.auth_form')

@section('title', 'Registration')

@section('content')
  <div class=" mt-5">
    <div class="row justify-content-center">
      <div class="p-3 ">
          <h2 class="text-center">Registration</h2>
          <p class="text-center">Fill in the details to register</p>

          {{-- Validation Errors --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold">Full Name</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Email Address</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Register</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection
