@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<div class="w3-main">
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
            </div>
            <div class="card-body">
                {{-- action="{{ route('users.store') }}" --}}
                <form id="userForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="userName" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="userName" name="name" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="userEmail" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="userEmail" name="email" placeholder="Enter email address" required>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="userPassword" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="userPassword" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="passwordConfirmation" class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="passwordConfirmation" name="password_confirmation" placeholder="Confirm password" required>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="userRole" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="userRole" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Delivery</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="userAvatar" class="form-label fw-bold">Profile Picture</label>
                        <input type="file" class="form-control" id="userAvatar" name="avatar" accept="image/*">
                    </div>

                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" id="saveBtn" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>