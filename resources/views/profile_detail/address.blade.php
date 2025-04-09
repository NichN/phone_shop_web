@extends('Layout.sidebar')

@section('title', 'Address Settings')

@section('content')
<div class="container">
    <div class="text-center mb-4">
        <h1>My Address</h1>
        <p class="text-muted">Manage your shipping address</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('address.store') }}" method="POST">
        @csrf

        <!-- Address Line 1 -->
        <div class="mb-3">
            <label class="form-label">Address Line 1*</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                       name="address_line1" value="{{ old('address_line1', auth()->user()->address_line1 ?? '') }}" 
                       placeholder="Street address" id="addressLine1Input" readonly required>
                <button class="btn btn-danger" type="button" id="addressLine1EditBtn">
                    Edit
                </button>
            </div>
        </div>

        <!-- Address Line 2 -->
        <div class="mb-3">
            <label class="form-label">Address Line 2</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                       name="address_line2" value="{{ old('address_line2', auth()->user()->address_line2 ?? '') }}" 
                       placeholder="Apartment, suite" id="addressLine2Input" readonly>
                <button class="btn btn-danger" type="button" id="addressLine2EditBtn">
                    Edit
                </button>
            </div>
        </div>

        <!-- City -->
        <div class="mb-3">
            <label class="form-label">City*</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                       name="city" value="{{ old('city', auth()->user()->city ?? '') }}" 
                       placeholder="City" id="cityInput" readonly required>
                <button class="btn btn-danger" type="button" id="cityEditBtn">
                    Edit
                </button>
            </div>
        </div>

        <!-- State -->
        <div class="mb-3">
            <label class="form-label">State/Province*</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                       name="state" value="{{ old('state', auth()->user()->state ?? '') }}" 
                       placeholder="State" id="stateInput" readonly required>
                <button class="btn btn-danger" type="button" id="stateEditBtn">
                    Edit
                </button>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-success">
                Save Address
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
            if (input.tagName === 'SELECT') {
                input.disabled = !input.disabled;
            } else {
                input.readOnly = !input.readOnly;
            }
            
            this.textContent = (input.readOnly || input.disabled) ? 'Edit' : 'Cancel';
            this.classList.toggle('btn-warning', !(input.readOnly || input.disabled));
            this.classList.toggle('btn-danger', (input.readOnly || input.disabled));
            
            if (!input.readOnly && !input.disabled) {
                input.focus();
            }
        });
    }

    // Initialize all edit buttons
    setupEditButton('addressLine1EditBtn', 'addressLine1Input');
    setupEditButton('addressLine2EditBtn', 'addressLine2Input');
    setupEditButton('cityEditBtn', 'cityInput');
    setupEditButton('stateEditBtn', 'stateInput');
</script>
@endsection