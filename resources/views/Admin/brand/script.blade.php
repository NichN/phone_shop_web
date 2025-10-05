<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script>
$(document).ready(function(){
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('brand.index') }}",
        columns: [
            { data: null, name: 'id', render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {data: 'logo', render: function(data) {
                        console.log(data);
                        return data ? `<img src="/storage/${data}" width="50">` : 'No Image';
                    }},
            { data: 'name', name: 'name' },
            // { data: 'description', name: 'description' },
            {
                data: 'created_at',
                name: 'created_at',
                    render: (data) => data ? new Date(data).toLocaleDateString('en-GB') : ''
            },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
    if ($('#brandForm').length) {
            $('#brandForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Category added successfully!',
                                willClose: () => {
                                    window.location.href = "{{ route('brand.index') }}";
                                }
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
        }

    // Delete Brand
    $(document).on('click', '.deleteBrand', function(){
        var brandId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('brand.delete', ':id') }}".replace(':id', brandId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response){
                        Swal.fire('Deleted!', 'The brand has been deleted.', 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr){
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete.', 'error');
                    }
                });
            }
        });
    });
    $('body').on('click', '.editBrand', function() {
        let id = $(this).data('id');
        let url = "{{ route('brand.edit', ':id') }}".replace(':id', id);
        $.get(url, function(data) {
            $('#brandId').val(data.id);
            $('#editName').val(data.name);
            $('#editLogo').val(data.logo);
            $('#edit_description').val(data.description);
            $('#editModal').modal('show');
        });
    });
    $('body').on('click', '.viewBrand', function () {
    let id = $(this).data('id');
    let url = "{{ route('brand.get_brand', ':id') }}".replace(':id', id);

    $.get(url)
        .done(function (response) {
            if (response.success) {
                let data = response.data;

                $('#viewBrandId').val(data.id);
                $('#viewBrandName').text(data.name);
                $('#viewBrandDescription').text(data.description);

                if (data.logo) {
                    $('#viewBrandLogo').html(`<img src="/storage/${data.logo}" alt="Brand Logo" style="max-width: 100%; height: auto;">`);
                } else {
                    $('#viewBrandLogo').html('<p class="text-muted">No Logo Available</p>');
                }

                $('#viewModal').modal('show');
            } else {
                alert('Brand not found.');
            }
        })
        .fail(function (xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('Failed to fetch brand details');
        });
});


    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#brandId').val();
        let url = "{{ route('brand.update', ':id') }}".replace(':id', id);
        var formData = new FormData(this);
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Category updated successfully!',
                        willClose: () => {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Update failed'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred during update'
                });
            }
        });
    });
});
</script>