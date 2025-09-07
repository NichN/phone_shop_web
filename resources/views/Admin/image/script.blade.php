<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<script>
$(document).ready(function(){

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
                        return meta.row + 1; // Serial number
                    }
                },
                {
                    data: 'file_path',
                    name: 'file_path',
                    className: 'text-center',
                    render: function(data) {
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
                            render: function(data, type, row) {
                                var checked = data ? 'checked' : '';
                                return `
                                    <label class="switch">
                                        <input type="checkbox" 
                                            class="toggle-default" 
                                            data-id="${row.id}" 
                                            data-type="${row.img_type}" 
                                            ${checked}>
                                        <span class="slider round"></span>
                                    </label>
                                `;
                            }
                        },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    }

    // Handle Add Image form
    if ($('#saveBtn').length){
        $('#imageForm').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData:false,
                contentType: false,
                success:function(response){
                    if(response.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Image added successfully!',
                            willClose: () => { window.location.href = "{{ route('photo.index') }}"; }
                        });
                    }
                },
                error:function(xhr){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred'
                    });
                }
            });
        });
    }

    // Toggle default image
    $(document).on('change', '.toggle-default', function() {
        var bannerId = $(this).data('id');
        var isChecked = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: `/photo/update-featured-status/${bannerId}`,
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                is_default: isChecked
            },
            success: function(response) {
                if(response.success) {
            
                    console.log('Banner updated!');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update banner.'
                });
            }
        });
    });
    $(document).ready(function(){

    // Open edit modal
    $(document).on('click', '.editImage', function(){
        var id = $(this).data('id');

        $.get(`/photo/edit/${id}`, function(data){
        $('#edit_image_id').val(data.id);
        $('#edit_name').val(data.name);
        $('#edit_img_type').val(data.img_type);
        $('#edit_title').val(data.caption);        
        $('#edit_description').val(data.description); 

    if(data.file_path){
        $('#currentImage').html(`<img src="/storage/${data.file_path}" width="100">`);
    } else {
        $('#currentImage').html('');
    }

    if(data.is_default == 1){
        $('#edit_defaultYes').prop('checked', true);
    } else {
        $('#edit_defaultNo').prop('checked', true);
    }

    $('#editImageModal').modal('show');
});

    });

    // Submit edit form
    $('#editImageForm').on('submit', function(e){
        e.preventDefault();
        var id = $('#edit_image_id').val();
        var formData = new FormData(this);

        $.ajax({
            url: `/photo/update/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.success){
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
            error: function(xhr){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update image'
                });
            }
        });
    });

});




    // Delete image
    $(document).on('click', '.deleteImage', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        let url = "{{ route('photo.delete', ':id') }}".replace(':id', id);

        $.ajax({
            url: url,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#imageForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Image deleted successfully!',
                        willClose: () => { window.location.href = "{{ route('photo.index') }}"; }
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            }
        });
    });

});
</script>

<style>
.text-center {
    text-align: center;
}
</style>
