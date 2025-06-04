<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script>
    $(document).ready(function(){
        if($('.data-table').length){
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('size.index') }}",
                columns:[
                    {data: 'id',name: 'id',render: function (data, type, row, meta) {
                        return meta.row + 1;}},
                    {data: 'size' , name: 'size'},
                    {data: 'created_at' , name: 'created_at'},
                    {data: 'action', orderable: false, searchable: false}
                ]
                
            });
        }
        if($('#sizeForm').length){
            $('#sizeForm').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
                console.log(formData);
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
                                text: 'Supplier added successfully!',
                                willClose: () => {
                                    window.location.href = "{{ route('size.index') }}";
                                }
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
        $('body').on('click', '.editsize', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $("#sizeId").val(id);
        let url = "{{ route('size.edit', ':id') }}".replace(':id', id);
            $.get(url,function(data) {
                    $('#sizeId').val(data.id);
                    $('#editsize').val(data.size);
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
        let url = "{{ route('size.update', ':id') }}".replace(':id', id);
        $.ajax({
            url,
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            dataType: 'json',
            success(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Size updated successfully!',
                        willClose: () => window.location.href = "{{ route('size.index') }}"
                    });
                }
            },
            error(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            }
        });
    });
    });
    $(document).on('click', '.deletesize', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        let url = "{{ route('size.delete', ':id') }}".replace(':id', id);

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Size deleted successfully!',
                        willClose: () => {
                            window.location.href = "{{ route('size.index') }}";
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
</script>