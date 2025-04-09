@extends('Layout.sidebar')

@section('title', 'Password Settings')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1>Password Settings</h1>
        <p class="text-muted">Manage your account password</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Current Password Field -->
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <div class="input-group">
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                       name="current_password" placeholder="Enter current password" id="currentPasswordInput" required>
                <button class="btn btn-danger" type="button" id="currentPasswordEditBtn">
                    Edit
                </button>
            </div>
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Password Field -->
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <div class="input-group">
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                       name="new_password" placeholder="Enter new password" id="newPasswordInput" required>
                <button class="btn btn-danger" type="button" id="newPasswordEditBtn">
                    Edit
                </button>
            </div>
            <small class="text-muted">Minimum 8 characters with at least one number and one special character.</small>
            @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm New Password Field -->
        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <div class="input-group">
                <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                       name="new_password_confirmation" placeholder="Confirm new password" id="confirmPasswordInput" required>
                <button class="btn btn-danger" type="button" id="confirmPasswordEditBtn">
                    Edit
                </button>
            </div>
            @error('new_password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-success">
                Update Password
            </button>
        </div>
    </form>
</div>

<script>
    // Edit Button Functionality
    function setupEditButton(btnId, inputId) {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        
        btn.addEventListener('click', function() {
            const isReadOnly = input.readOnly;
            
            input.readOnly = !isReadOnly;
            this.textContent = isReadOnly ? 'Cancel' : 'Edit';
            this.classList.toggle('btn-danger', !isReadOnly);
            this.classList.toggle('btn-warning', isReadOnly);
            
            if (!isReadOnly) {
                input.focus();
            }
        });
    }

    // Initialize all edit buttons
    setupEditButton('currentPasswordEditBtn', 'currentPasswordInput');
    setupEditButton('newPasswordEditBtn', 'newPasswordInput');
    setupEditButton('confirmPasswordEditBtn', 'confirmPasswordInput');
</script>
@endsection