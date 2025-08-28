<script>
    $(function () {
        let table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'group', name: 'group'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        // Open Add User Modal
        $(document).on('click', '#addUserBtn', function() {
            $('#userForm')[0].reset();
            $('#userId').val('');
            
            // Show password fields for new user
            $('.password-fields').show();
            $('.edit-password-section').hide();
            
            // Reset edit password section
            $('#changePasswordCheck').prop('checked', false);
            $('.edit-password-fields').hide();
            
            // Clear any validation states
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide();
            
            // Set required attributes for new user mode
            $('#userPassword').attr('required', true);
            $('#passwordConfirmation').attr('required', true);
            
            // Debug: Check if password fields exist
            console.log('Password field exists:', !!$('#userPassword').length);
            console.log('Password confirmation field exists:', !!$('#passwordConfirmation').length);
            console.log('Password field value:', $('#userPassword').val());
            
            $('#userModalLabel').text('Add User');
            $('#userModal').modal('show');
        });

        // Open Edit User Modal
        $(document).on('click', '.editUserBtn', function() {
            let id = $(this).data('id');
            $.get(`/admin/users/${id}/edit`, function(data) {
                $('#userId').val(data.id);
                $('#userName').val(data.name);
                $('#userEmail').val(data.email);
                $('#userRole').val(data.role_id);
                
                // Hide password fields for edit mode
                $('.password-fields').hide();
                $('.edit-password-section').show();
                
                // Reset edit password section
                $('#changePasswordCheck').prop('checked', false);
                $('.edit-password-fields').hide();
                
                // Clear password fields
                $('#editUserPassword').val('');
                $('#editPasswordConfirmation').val('');
                
                // Remove required attributes for edit mode (password is optional)
                $('#userPassword').attr('required', false);
                $('#passwordConfirmation').attr('required', false);
                
                // Clear any validation states
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').hide();
                
                $('#userModalLabel').text('Edit User');
                $('#userModal').modal('show');
            });
        });

        // Handle change password checkbox
        $(document).on('change', '#changePasswordCheck', function() {
            if ($(this).is(':checked')) {
                $('.edit-password-fields').show();
                $('#editUserPassword').attr('required', true);
                $('#editPasswordConfirmation').attr('required', true);
            } else {
                $('.edit-password-fields').hide();
                $('#editUserPassword').attr('required', false).val('');
                $('#editPasswordConfirmation').attr('required', false).val('');
            }
        });

        // Add/Edit User AJAX
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            
            console.log('User form submitted');
            console.log('Form ID:', this.id);
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Password confirmation validation for edit mode
            let id = $('#userId').val();
            if (id && $('#changePasswordCheck').is(':checked')) {
                let newPassword = $('#editUserPassword').val();
                let confirmPassword = $('#editPasswordConfirmation').val();
                
                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Password confirmation does not match!'
                    });
                    return false;
                }
                
                if (newPassword.length < 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Password must be at least 6 characters long!'
                    });
                    return false;
                }
            }
            
            // Password confirmation validation for add mode
            if (!id) {
                let password = $('#userPassword').val().trim();
                let confirmPassword = $('#passwordConfirmation').val().trim();
                
                console.log('Add mode - Password validation:', { password: !!password, confirmPassword: !!confirmPassword });
                
                // Check if password is empty
                if (!password) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Required',
                        text: 'Please enter a password for the new user.'
                    });
                    $('#userPassword').focus();
                    return false;
                }
                
                // Check password length
                if (password.length < 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Too Short',
                        text: 'Password must be at least 6 characters long.'
                    });
                    $('#userPassword').focus();
                    return false;
                }
                
                // Check if confirmation is empty
                if (!confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Confirmation Required',
                        text: 'Please confirm the password.'
                    });
                    $('#passwordConfirmation').focus();
                    return false;
                }
                
                // Check if passwords match
                if (password !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Passwords Do Not Match',
                        text: 'Password and confirmation password do not match!'
                    });
                    $('#passwordConfirmation').focus();
                    return false;
                }
            }
            
            let url = id ? `/admin/users/${id}` : `/admin/users`;
            let type = id ? 'POST' : 'POST';
            let method = id ? 'PUT' : 'POST';
            let formData = new FormData(this);
            if (id) formData.append('_method', 'PUT');
            
            // For edit mode, map edit password fields to backend expected names
            if (id && $('#changePasswordCheck').is(':checked')) {
                // Get edit password values
                let editPassword = $('#editUserPassword').val();
                let editPasswordConfirmation = $('#editPasswordConfirmation').val();
                
                // Remove the edit field names
                formData.delete('edit_password');
                formData.delete('edit_password_confirmation');
                
                // Add with backend expected names
                formData.append('password', editPassword);
                formData.append('password_confirmation', editPasswordConfirmation);
            } else if (id) {
                // Remove all password fields if not changing password
                formData.delete('password');
                formData.delete('password_confirmation');
                formData.delete('edit_password');
                formData.delete('edit_password_confirmation');
            }
            
            // Debug: Log what's being sent
            console.log('Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            $.ajax({
                url: url,
                type: type,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success || response.message) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.success || response.message,
                        });
                        $('#userModal').modal('hide');
                        table.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'An error occurred';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                }
            });
        });

        // SweetAlert Delete
        $(document).on('click', '.delete-btn', function () {
            let url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "You will not be able to recover this user!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {_token: '{{ csrf_token() }}'},
                        success: function (response) {
                            table.ajax.reload();
                            Swal.fire('Deleted!', response.success, 'success');
                        },
                        error: function (xhr) {
                            let msg = xhr.responseJSON?.error || 'Something went wrong.';
                            Swal.fire('Failed!', msg, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
