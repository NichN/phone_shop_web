@extends('Layout.auth_form')

@section('title', 'Two-Factor Verification')

@section('content')
    <div class="w-100">
        <div class="p-4 p-md-5">
            <h2 class="text-center mb-3 fw-bold">Two-Factor Verification</h2>
            <p class="text-center text-muted mb-4">Enter the 6-digit code sent to your email or phone</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('two_factor.verify') }}">
                @csrf

                <div class="mb-3">
                    <label for="code" class="form-label fw-semibold">Verification Code</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <input type="text" name="code" id="code" class="form-control"
                            placeholder="Enter your 6-digit code" maxlength="6" pattern="\d{6}" required autofocus>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">Verify</button>
                </div>
            </form>
        </div>
    </div>
@endsection
