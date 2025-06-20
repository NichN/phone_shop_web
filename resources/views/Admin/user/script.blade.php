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
            $('.password-fields').show();
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
                $('.password-fields').hide();
                $('#userModalLabel').text('Edit User');
                $('#userModal').modal('show');
            });
        });

        // Add/Edit User AJAX
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#userId').val();
            let url = id ? `/admin/users/${id}` : `/admin/users`;
            let type = id ? 'POST' : 'POST';
            let method = id ? 'PUT' : 'POST';
            let formData = new FormData(this);
            if (id) formData.append('_method', 'PUT');
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
                    let msg = xhr.responseJSON?.message || 'An error occurred';
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
                        error: function () {
                            Swal.fire('Failed!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
