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
                ajax: "{{ route('supplier.index') }}",
                columns:[
                    {data: 'id',name: 'id',render: function (data, type, row, meta) {
                        return meta.row + 1;}},
                    {data: 'name' , name: 'name'},
                    {data: 'address' , name: 'address'},
                    {data: 'phone' , name: 'phone'},
                    {data:'email' , name: 'email'},
                    {data: 'action', orderable: false, searchable: false}
                ]
                
            });
        }
        if($('#supplierForm').length){
            $('#supplierForm').on('submit',function(e){
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
                                    window.location.href = "{{ route('supplier.index') }}";
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
        $('body').on('click', '.editSupplier', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = "{{ route('supplier.edit', ':id') }}".replace(':id', id);
            $.get(url,function(data) {
                    $('#supplierId').val(data.id);
                    $('#edit_Name').val(data.name);
                    $('#edit_address').val(data.address);
                    $('#edit_phone').val(data.phone);
                    $('#edit_email').val(data.email);
                    $('#editModal').modal('show');
                }
            ).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            });
        });
        $('#editsupplierForm').on('submit',function(e){
            e.preventDefault();
            let url = "{{ route('supplier.update', ':id') }}".replace(':id', id);
            var formData = new FormData(this);
            $.ajax({
                url: url,
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
                            text: 'Supplier updated successfully!',
                            willClose: () => {
                                window.location.href = "{{ route('supplier.index') }}";
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

    });
    $(document).on('click', '.deleteSupplier', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        let url = "{{ route('supplier.delete', ':id') }}".replace(':id', id);

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
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Supplier deleted successfully!',
                        willClose: () => {
                            window.location.href = "{{ route('supplier.index') }}";
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