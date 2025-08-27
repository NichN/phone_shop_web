<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    var purchaseTable = $('.data-table.purchase').DataTable({
        processing: false,
        serverSide: true,
        paging: true,
        searching: true,
        info: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        ajax: {
            url: "{{ route('purchase.index') }}",
            dataSrc: 'data'
        },
        columns: [
            { data: 'id', name: 'id', render: function (data, type, row, meta) {
                return meta.row + 1;
            }},
            { data: 'name', name: 'name' },
            {data: 'color_code',name: 'color_code',
                render: function(data, type, row) {
                    if (!data) return '';

                    return `
                        <span class="d-inline-block rounded-circle" 
                            style="width: 20px; height: 20px; background-color: ${data}; border: 1px solid #ccc;">
                        </span>
                    `;
                }
            },
            { data: 'size', name: 'size' },
            { data: 'quantity', name: 'quantity' },
            { data: 'cost_price', name: 'cost_price'},
            { data: 'subtotal', name: 'subtotal' },
            { data: 'action', orderable: false, searchable: false }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var totalPaid = api.column(6, { page: 'f' }).data().reduce(function (a, b) {
                var value = typeof b === 'string' ? b.replace(/[^0-9.-]+/g, "") : b;
                return a + (parseFloat(value) || 0);
            }, 0);
            $('#totalPaymentFooter').html('Total: ' + totalPaid.toFixed(2));
            localStorage.setItem('purchase_total', totalPaid.toFixed(2));
            console.log(localStorage.getItem('purchase_total'));

        }
    });
    $('#purForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function () {
                purchaseTable.ajax.reload();
                $('#purForm')[0].reset();
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
    $('#pur_product').select2({
        placeholder: "Search Product Name...",
        allowClear: true,
        maximumSelectionLength: 1
    });

    function loadProduct(searchTerm = '') {
        $.ajax({
            url: "{{ route('purchase.search-product') }}",
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                $('#pur_product').empty().append('<option value="">Select Product</option>');
                response.forEach(function(item) {
                    const label = `${item.product_name} ${item.size ? '- ' + item.size : ''} ${item.colors ? '- ' + item.colors : ''}`;
                    $('#pur_product').append(`<option value="${item.id}">${label}</option>`);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error loading Product:", error);
            }
        });
    }
    loadProduct();

    $(document).on('click', '.delete', function () {
        var pr_itemID = $(this).data('id');
        $.ajax({
            url: "{{ route('purchase.destroy', ':id') }}".replace(':id', pr_itemID),
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function () {
                purchaseTable.ajax.reload(null, false);
            }
        });
    });
    $('#qty').on('input', function () {
        let qty = parseFloat($('#qty').val()) || 0;
        let unit_price = parseFloat($('#unitprice').val()) || 0;
        let total = qty * unit_price;
        $('#unitprice').val(total.toFixed(2));
    });

    window.changeProduct = function () {
        let pr_itemID = $('#pur_product').val();
        $.ajax({
            url: "{{ route('purchase.get_product_item', '') }}/" + pr_itemID,
            method: 'GET',
            success: function(data) {
                $('#unitprice').val(data.cost_price);
                $('#color').val(data.color_name);
            },
        });
    };
    var paymentTable = $('.data-table.payment').DataTable({
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
            { data: 'Grand_total', name: 'Grand_total' },
            { data: 'paid', name: 'paid' },
            { data: 'balance', name: 'balance'},
            {data: 'payment_statuse',render: function(data, type, row) {
            let color = '';
            if (data === 'Paid') color = 'style="background-color:green;"';
            else if (data === 'Partially') color = 'style="background-color:yellow;"';
            else if (data === 'Unpaid') color = 'style="background-color:red;"';
            return `<span ${color}>${data}</span>`;}
            },
            { data: 'action', orderable: false, searchable: false }
        ],
    });
    let grandTotal = localStorage.getItem('purchase_total');
        if (grandTotal !== null) {
            $('#grand_total').val(grandTotal);
        }
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
                success: function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Purchase added successfully!'
                    }).then(() => {
                        window.location.href = "{{ route('purchase.add') }}";
                    });
                    paymentTable.ajax.reload();
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
        //​គណនាតម្លៃ balance
        $('#paid').on('input', function () {
            let paid = parseFloat($('#paid').val()) || 0;
            let grand_total = parseFloat($('#grand_total').val()) || 0;
            let balance = grand_total - paid;
            $('#balance').val(balance.toFixed(2));
        });
        // គណនាតម្លៃលុយនៅសល់ក្នុងក្នុង​Add Payment
        $('#paid1').on('input', function () {
            let paid = parseFloat($('#paid1').val()) || 0;
            let grand_total = parseFloat($('#remaining').val()) || 0;
            let balance = grand_total - paid;
            $('#balance').val(balance.toFixed(2));
        });
        $(document).on('click', '.delete', function () {
        var productID = $(this).data('id');

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
                    url: "{{ route('purchase.delete_purchases', ':id') }}".replace(':id', productID),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire('Deleted!', 'The product has been deleted.', 'success');
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete.', 'error');
                    }
                });
            }
        });
    });
     $(document).on('click', '.addpayment', function () {
        let url = $(this).data('url');
        window.location.href = url;
    });
    $('#addpaymentForm').on('submit', function(e){
        e.preventDefault();
        let id = $('#purchase_id').val();
        let url = "{{ route('purchase.updatepayment', ':id') }}".replace(':id', id);
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
    $(document).on('click', '.showpurachse', function () {
        let url = $(this).data('url');
        window.location.href = url;
    });
    $('#showInvoice').on('submit', function(e){
        e.preventDefault();
        let id = $('#purchase_id').val();
        let url = "{{ route('purchase.purchase_invoice', ':id') }}".replace(':id', id);
        var formData = new FormData(this);
        $.ajax({
            url: url,
            type: 'GET',
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
    function printInvoice() {
    var printContents = document.getElementById('invoiceModal').cloneNode(true);
    var buttons = printContents.querySelectorAll('.btn-close, .btn-secondary');
    buttons.forEach(function(button) {
        button.remove();
    });
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents.innerHTML;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
    
</script>
