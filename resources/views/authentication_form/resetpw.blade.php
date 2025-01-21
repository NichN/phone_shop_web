@extends('Layout.auth_form')

@section('title', 'Reset Password')

@section('content')
<div class="mt-5">
  <div class="row justify-content-center">
    <div class="mt-0 text-style">
        <span><i class="fa-solid fa-arrow-left"></i></span>
        <a href="{{route('login')}}" class="text-decoration-none text-muted">Back</a>
    </div>
    <div class="p-5">
      <h2 class="text-center">Reset Password</h2>
      <p class="text-center">Enter your new password</p>
      {{-- action="{{ route('password.update') }}" --}}
      <form method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ request()->query('token') }}">
        <div class="mb-3">
          <label for="password" class="form-label fw-semibold">New Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
          </div>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
