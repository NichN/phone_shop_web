<script>
    $(document).ready(function () {
        // Initialize DataTable
        if ($('.data-table').length) {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('supplier.index') }}",
                order: [[0, 'desc']],
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'name', name: 'name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'email', name: 'email' },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });
        }

        // Create Supplier
        if ($('#supplierForm').length) {
            $('#supplierForm').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Supplier added successfully!',
                                willClose: () => {
                                    window.location.href = "{{ route('supplier.index') }}";
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'An error occurred'
                        });
                    }
                });
            });
        }

        // Edit Supplier
        $('body').on('click', '.editSupplier', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let url = "{{ route('supplier.edit', ':id') }}".replace(':id', id);

            $.get(url, function (data) {
                $('#supplierId').val(data.id);
                $('#edit_Name').val(data.name);
                $('#edit_address').val(data.address);
                $('#edit_phone').val(data.phone);
                $('#edit_email').val(data.email);
                $('#editModal').modal('show');
            }).fail(function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            });
        });

        // Update Supplier
        $('#editsupplierForm').on('submit', function (e) {
            e.preventDefault();
            let id = $('#supplierId').val();
            let url = "{{ route('supplier.update', ':id') }}".replace(':id', id);
            var formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Supplier updated successfully!',
                            willClose: () => {
                                window.location.href = "{{ route('supplier.index') }}";
                            }
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred'
                    });
                }
            });
        });

        // Delete Supplier
        $(document).on('click', '.deleteSupplier', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            let url = "{{ route('supplier.delete', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Supplier deleted successfully!',
                                    willClose: () => {
                                        window.location.href = "{{ route('supplier.index') }}";
                                    }
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'An error occurred'
                            });
                        }
                    });
                }
            });
        });

        // View Supplier Details
        $('body').on('click', '.viewSupplier', function () {
            let id = $(this).data('id');
            let url = "{{ route('supplier.get_supplier', ':id') }}".replace(':id', id);

            $.get(url)
                .done(function (response) {
                    if (response.success) {
                        let data = response.data;
                        $('#viewSupplierId').val(data.id);
                        $('#viewSupplierName').text(data.name);
                        $('#viewSupplierAddress').text(data.address);
                        $('#viewSupplierPhone').text(data.phone);
                        $('#viewSupplierEmail').text(data.email);
                        $('#viewSupplierModal').modal('show');
                    } else {
                        alert('Supplier not found.');
                    }
                })
                .fail(function (xhr, status, error) {
                    console.error('Error fetching supplier:', error);
                    alert('Failed to fetch supplier details.');
                });
        });

    });
</script>
