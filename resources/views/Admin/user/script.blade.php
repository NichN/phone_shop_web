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
