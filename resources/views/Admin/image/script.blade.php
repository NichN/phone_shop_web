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
                ajax: "{{ route('photo.index') }}",
                columns:[
                    {data: 'id',name: 'id',render: function (data, type, row, meta) {
                        return meta.row + 1;}},
                    {data: 'file_path', render: function(data) {
                        return data ? `<img src="/storage/${data}" width="50">` : 'No Image';
                    }},
                    {data: 'name' , name: 'name'},
                    {data: 'action', orderable: false, searchable: false}
                ]
                
            });
        }
        if ($('#saveBtn').length){
            $('#imageForm').on('submit',function(e){
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
                                text: 'Image added successfully!',
                                willClose: () => {
                                    window.location.href = "{{ route('photo.index') }}";
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
    });
    $(document).on('click', '.deleteImage', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        let url = "{{ route('photo.delete', ':id') }}".replace(':id', id);

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
                        text: 'image deleted successfully!',
                        willClose: () => {
                            window.location.href = "{{ route('photo.index') }}";
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