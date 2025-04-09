@extends('Layout.sidebar')

@section('title', 'User Email')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1>My Email</h1>
        <p class="text-muted">Manage your email address</p>
    </div>

    <form action="#" method="POST">
        @csrf
        @method('PUT')

        <!-- Email Field -->
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
                <input type="email" class="form-control" name="email" placeholder="your@email.com" id="emailInput" readonly>
                <button class="btn btn-danger" type="button" id="emailEditBtn">
                    Edit
                </button>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-success">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('emailEditBtn').addEventListener('click', function() {
        const emailInput = document.getElementById('emailInput');
        const isReadOnly = emailInput.readOnly;
        
        emailInput.readOnly = !isReadOnly;
        this.textContent = isReadOnly ? 'Cancel' : 'Edit';
        this.classList.toggle('btn-danger', !isReadOnly);
        this.classList.toggle('btn-warning', isReadOnly);
        
        if (!isReadOnly) {
            emailInput.focus();
        }
    });
</script>
@endsection