<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
       var table = $('.data-table').DataTable({
            processing: false,
            serverSide: true,
            ajax: {
                url: "{{ route('purchase.payment') }}",
                dataSrc: 'data'
            },
            columns: [
                { data: 'id', name: 'id', render: function (data, type, row, meta) {
                    return meta.row + 1;
                }},
                { data: 'reference_no', name: 'reference_no' },
                { data: 'supplier', name: 'supplier' },
                { data: 'Grand_total', name: 'Grand_total' },
                { data: 'paid', name: 'paid' },
                { data: 'balance', name: 'balance'},
                { data: 'payment_statuse', name: 'payment_statuse' },
                { data: 'action', orderable: false, searchable: false }
            ],
        });
        $('#paymentForm').on('submit', function (e) {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Purchase added successfully!'
                    });
                    table.ajax.reload();
                    $('#paymentForm')[0].reset();
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
    });
</script>
