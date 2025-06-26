$(function () {
    let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/admin/roles/data',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    // Open Add Role Modal
    $(document).on('click', '#addRoleBtn', function() {
        $('#roleForm')[0].reset();
        $('#roleId').val('');
        $('#roleModalLabel').text('Add Role');
        $('#roleModal').modal('show');
    });

    // Open Edit Role Modal
    $(document).on('click', '.editRoleBtn', function() {
        let id = $(this).data('id');
        $.get(`/admin/roles/${id}/edit`, function(data) {
            $('#roleId').val(data.id);
            $('#roleName').val(data.name);
            $('#roleModalLabel').text('Edit Role');
            $('#roleModal').modal('show');
        });
    });

    // Add/Edit Role AJAX
    $('#roleForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#roleId').val();
        let url = id ? `/admin/roles/${id}` : `/admin/roles`;
        let type = id ? 'POST' : 'POST';
        let formData = $(this).serialize();
        if (id) formData += '&_method=PUT';
        $.ajax({
            url: url,
            type: type,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success || response.message) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.success || response.message,
                    });
                    $('#roleModal').modal('hide');
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
    $(document).on('click', '.deleteRoleBtn', function () {
        let url = $(this).data('url');
        Swal.fire({
            title: 'Are you sure?',
            text: "You will not be able to recover this role!",
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
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
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