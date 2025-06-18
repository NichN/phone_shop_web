<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<script>
    $(document).ready(function () {
        if ($('.data-table').length) {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('faq.index') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'question', name: 'question' },
                    { data: 'answer', name: 'answer' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        }

        if ($('#faqForm').length) {
            $('#faqForm').on('submit', function (e) {
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
                                text: 'FAQ added successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#faqForm')[0].reset();
                            $('#addModal').modal('hide');
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
            });
        }

        $(document).on('click', '.deletefaq', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            let url = "{{ route('faq.delete', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
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
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'FAQ deleted successfully!'
                                });
                                $('.data-table').DataTable().ajax.reload(null, false);
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
                }
            });
        });
        $('body').on('click', '.editfaq', function() {
        let id = $(this).data('id');
        let url = "{{ route('faq.edit', ':id') }}".replace(':id', id);
        $.get(url, function(data) {
            $('#faqID').val(data.id);
            $('#question_edit').val(data.question);
            $('#answer_edit').val(data.answer);
            $('#editModal').modal('show');
        });
    });
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#faqID').val();
        let url = "{{ route('faq.update', ':id') }}".replace(':id', id);
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
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Successfully updated!',
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#editForm')[0].reset();
                $('#editModal').modal('hide');
                $('.data-table').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Update failed'
                });
            }
        }
        });
    });
    });
</script>
