@extends('Layout.sidebar')

@section('title', 'User Profile')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1>My Profile</h1>
        <p class="text-muted">Manage your personal information</p>
    </div>

    <form action="" method="POST">
        @csrf
        @method('PUT')

        <!-- Profile Picture -->
        <div class="text-center mb-4">
            <img src="{{ asset('image/airpods.jpg') }}" alt="Profile Picture" 
                 class="rounded-circle" width="120" height="120">
            <div class="mt-2">
                <button type="button" class="btn btn-outline-danger btn-sm">
                    Change Photo
                </button>
            </div>
        </div>

        <!-- Name Field -->
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <div class="input-group">
                <input type="text" class="form-control" name="name" placeholder="Enter Your Name" id="nameInput" readonly>
                <button class="btn btn-danger" type="button" id="nameEditBtn">
                    Edit
                </button>
            </div>
        </div>

        <!-- Phone Field -->
        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <div class="input-group">
                <input type="text" class="form-control" name="phone" placeholder="Enter Your Number" id="phoneInput" readonly>
                <button class="btn btn-danger" type="button" id="phoneEditBtn">
                    Edit
                </button>
            </div>
            <small class="text-muted">We collect this in case of emergencies.</small>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-success">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    // Function to handle edit buttons
    function setupEditButton(btnId, inputId) {
        document.getElementById(btnId).addEventListener('click', function() {
            const input = document.getElementById(inputId);
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
    setupEditButton('nameEditBtn', 'nameInput');
    setupEditButton('phoneEditBtn', 'phoneInput');
</script>
@endsection