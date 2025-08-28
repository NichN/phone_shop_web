@include('Admin.component.sidebar');
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">User List</h4>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-primary" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#userModal">
                <i class="fas fa-plus me-2"></i>Add User
            </button>
        </div>   
        <div class="container no-print">
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3">
                        <thead style="text-align:center;">
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Name</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Email</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Group</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>                                
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="userForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="userId" name="id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="userName" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="userName" name="name" required>
            <div class="invalid-feedback">Name is required.</div>
          </div>
          <div class="mb-3">
            <label for="userEmail" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="userEmail" name="email" required>
            <div class="invalid-feedback">Valid email is required.</div>
          </div>
          <div class="mb-3 password-fields">
            <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="userPassword" name="password" required minlength="6">
            <div class="invalid-feedback">Password is required and must be at least 6 characters long.</div>
          </div>
          <div class="mb-3 password-fields">
            <label for="passwordConfirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="passwordConfirmation" name="password_confirmation" required>
            <div class="invalid-feedback">Please confirm your password.</div>
          </div>
          <!-- Edit Password Section (Hidden by default, shown during edit) -->
          <div class="mb-3 edit-password-section" style="display: none;">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="changePasswordCheck">
              <label class="form-check-label" for="changePasswordCheck">
                Change Password
              </label>
            </div>
            <div class="edit-password-fields" style="display: none;">
              <div class="mb-3">
                <label for="editUserPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="editUserPassword" name="edit_password" minlength="6">
                <small class="form-text text-muted">Leave blank to keep current password</small>
              </div>
              <div class="mb-3">
                <label for="editPasswordConfirmation" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="editPasswordConfirmation" name="edit_password_confirmation">
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="userRole" class="form-label">Group</label>
            <select class="form-control" id="userRole" name="role">
                <option value="">No Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="userAvatar" class="form-label">Profile Picture</label>
            <input type="file" class="form-control" id="userAvatar" name="avatar" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="saveUserBtn">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
<style>
    .text-danger {
        color: #dc3545 !important;
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }
    
    .password-fields {
        transition: all 0.3s ease;
    }
    
    .edit-password-section {
        transition: all 0.3s ease;
    }
</style>

@include('Admin.user.script')