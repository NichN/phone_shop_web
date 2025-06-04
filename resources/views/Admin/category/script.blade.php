<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script>
    $(document).ready(function() {
        if ($('.data-table').length) {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('category.index') }}",
                columns: [
                    {data: 'id', name: 'id',render: function (data, type, row, meta) {
                        return meta.row + 1;}},
                    {data: 'image', render: function(data) {
                        return data ? `<img src="/storage/${data}" width="50">` : 'No Image';
                    }},
                    {data: 'name'},
                    {data: 'description'},
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: (data) => data ? new Date(data).toLocaleDateString('en-GB') : ''
                    },
                    {data: 'action', orderable: false, searchable: false}
                ]
            });
        }

        if ($('#saveBtn').length) {
            $('#categoryForm').on('submit', function(e) {
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
                                    window.location.href = "{{ route('category.index') }}";
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
        $('body').on('click','.deleteCate',function(){
            let id = $(this).data('id');
            let url ="{{route('category.delete', ':id')}}";
            if(confirm("Are You Sure ?")){
                $.ajax({
                    url:url.replace(':id', id),
                    type:'DELETE',
                    dataType:'json',
                    data:{_token:'{{ csrf_token() }}'},
                    success:function(response){
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Category deleted successfully!',
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                })
            }
        })
        $('body').on('click', '.editCate', function() {
        let id = $(this).data('id');
        let url = "{{ route('category.edit', ':id') }}".replace(':id', id);
        $.get(url, function(data) {
            $('#cateId').val(data.id);
            $('#editName').val(data.name);
            $('#edit_description').val(data.description);
            $('#edit_photo').html(data.image ? `<img src="/storage/${data}" width="50">` : 'No Image');
            $('#editModal').modal('show');
        });
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#cateId').val();
        let url = "{{ route('category.update', ':id') }}".replace(':id', id);
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