<script>
$(document).ready(function () {

    // Initialize DataTable
    if ($('.data-table').length) {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('photo.index') }}",
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    className: 'text-center',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'file_path',
                    name: 'file_path',
                    className: 'text-center',
                    render: function (data) {
                        return data ? `<img src="/storage/${data}" width="50">` : 'No Image';
                    }
                },
                { data: 'name', name: 'name', className: 'text-center' },
                { data: 'caption', name: 'title', className: 'text-center' },
                { data: 'description', name: 'description', className: 'text-center' },
                { data: 'img_type', name: 'img_type', className: 'text-center' },
                {
                    data: 'is_default',
                    name: 'is_default',
                    className: 'text-center',
                    render: function (data, type, row) {
                        var checked = data ? 'checked' : '';
                        return `
                            <label class="switch">
                                <input type="checkbox" class="toggle-default" 
                                    data-id="${row.id}" data-type="${row.img_type}" ${checked}>
                                <span class="slider round"></span>
                            </label>
                        `;
                    }
                },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    }

    // Initialize Select2 (Create Modal)
    $('#create_product_item_id').select2({
        dropdownParent: $('#addModal')
    });

    // Initialize Select2 (Edit Modal - dynamically)
    $('#editImageModal').on('shown.bs.modal', function () {
        $('#edit_product_item_id').select2({
            dropdownParent: $('#editImageModal')
        });
    });

    // Submit Create Form
    $('#imageForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Image added successfully!',
                        willClose: () => {
                            window.location.href = "{{ route('photo.index') }}";
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

    // Open Edit Modal
    $(document).on('click', '.editImage', function () {
        var id = $(this).data('id');
         $('#editImageForm')[0].reset();  // reset inputs
    $('#currentImage').html('');     // clear current image preview
    $('#edit_product_item_id').val(null).trigger('change');

        $.get(`/photo/edit/${id}`, function (data) {
            $('#edit_image_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#edit_img_type').val(data.img_type);
            $('#edit_title').val(data.caption);
            $('#edit_description').val(data.description);
            $('#edit_product_item_id').val(data.product_item_id).trigger('change');

            if (data.file_path) {
                $('#currentImage').html(`<img src="/storage/${data.file_path}" width="100">`);
            } else {
                $('#currentImage').html('');
            }

            if (data.is_default == 1) {
                $('#edit_defaultYes').prop('checked', true);
            } else {
                $('#edit_defaultNo').prop('checked', true);
            }

            $('#editImageModal').modal('show');
        });
    });

    // Submit Edit Form
    $('#editImageForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#edit_image_id').val();
        var formData = new FormData(this);

        $.ajax({
            url: `/photo/update/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Image updated successfully!',
                        timer: 1000,
                        showConfirmButton: false
                    });
                    $('#editImageModal').modal('hide');
                    $('.data-table').DataTable().ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update image'
                });
            }
        });
    });

    // Toggle Default Image
    $(document).on('change', '.toggle-default', function () {
        var bannerId = $(this).data('id');
        var isChecked = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: `/photo/update-featured-status/${bannerId}`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                is_default: isChecked
            },
            success: function (response) {
                if (response.success) {
                    console.log('Banner updated!');
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update banner.'
                });
            }
        });
    });

    // Delete Image
    $(document).on('click', '.deleteImage', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "{{ route('photo.delete', ':id') }}".replace(':id', id);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This image will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Image deleted successfully!',
                                timer: 1000,
                                showConfirmButton: false
                            });
                            $('.data-table').DataTable().ajax.reload(null, false);
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

});
</script>
