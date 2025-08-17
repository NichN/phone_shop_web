<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script>
    $(document).ready(function(){
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.product_index')}}",
            order: [[0, 'desc']],
            columns:[
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name', name: 'name' },
                { data: 'brandname', name: 'brandname' },
                { data: 'categoryname', name: 'categoryname' },
                { data: 'description' , name:'description'},
                { data: 'action', orderable: false, searchable: false }
            ]
        });
        if($('#productForm').length){
            $('#productForm').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Product added successfully!',
                            });
                            $('#productForm')[0].reset();

                            $('.data-table').DataTable().ajax.reload(null, false);
                            $('#productForm select').val(null).trigger('change');
                            $('#addModal').modal('hide');
                        }
                    },
                    error: function(xhr){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'An error occurred'
                        });
                    }
                });
            });
        }
        });
        $('body').on('click', '.deleteProduct', function() {
                let id = $(this).data('id');
                let url = "{{ route('products.deleteproduct', ':id') }}";

                $.ajax({
                    url: url.replace(':id', id),
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Product deleted successfully!',
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }
                });
            });

        $('body').on('click', '.editProduct', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $("#productId").val(id);
        let url = "{{ route('products.product_edit', ':id') }}".replace(':id', id);
            $.get(url,function(data) {
                    $('#productId').val(data.id);
                    $('#edit_prName').val(data.name);
                    $('#edit_brand_id').val(data.brand_id);
                    $('#edit_description').val(data.description);
                    $('#edit_cat_id').val(data.cat_id);
                    $('#editModal').modal('show');
                    $('#editForm').data('id', data.id);
                }
            ).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            });
        });
        $('#editForm').submit(function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = "{{ route('products.product_update', ':id') }}".replace(':id', id);
        $.ajax({
            url,
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            dataType: 'json',
            success(res) {
                if (res.success) {
                    $('#editModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product updated successfully!',
                        willClose: () => window.location.href = "{{ route('products.product_index') }}"
                    });
                }
            },
            error(xhr) {
                $('#editModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Please Enter Brand',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            }
        });
    });
    
</script>
