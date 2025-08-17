@include('Admin.component.sidebar')

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">

<!-- Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Custom CSS (Optional) -->
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<div class="w3-main">
    <a href="/report/supplier" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h6>Supplier Purchase Detail</h6> 
            </div>
            <div class="card-body">
                <div style="overflow-x:auto;">
                    <table class="table table-bordered data-table text-center">
                        <thead>
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Reference No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Supplier</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Product</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Grand Total</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Paid</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Balance</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="4" style="text-align:right">Total:</th>
                                <th id="total-grand"></th>
                                <th id="total-paid"></th>
                                <th id="total-balance"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<!-- JSZip for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- SweetAlert2 (optional) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('report.supplier_view', ['id' => $supplier->id]) }}",
        order: [[0, 'desc']],
        dom: "<'row'<'col-md-6'l><'col-md-6 d-flex justify-content-end gap-2'B>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-md-5'i><'col-md-7'p>>",
        buttons: [
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                className: 'btn btn-custom',
                titleAttr: 'Print'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel"></i>',
                className: 'btn btn-custom',
                titleAttr: 'Export to Excel'
            }
        ],
        columns: [
            {data:'purchase_date', name:'purchase_date'},
            {data:'reference_no', name:'reference_no'},
            {data:'name', name:'name'},
            {data:'products', name:'products'},
            {data:'Grand_total', name:'Grand_total'},
            {data:'paid', name:'paid'},
            {data:'balance', name:'balance'},
            {data:'payment_statuse', name:'payment_statuse'}
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            // Helper function to sum column
            var intVal = function (i) {
                return typeof i === 'string'
                    ? parseFloat(i.replace(/[\$,]/g, '')) || 0
                    : typeof i === 'number'
                    ? i
                    : 0;
            };

            // Grand Total
            var grandTotal = api
                .column(4, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Paid
            var paidTotal = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Balance
            var balanceTotal = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Display in footer
            $('#total-grand').html(grandTotal.toFixed(2));
            $('#total-paid').html(paidTotal.toFixed(2));
            $('#total-balance').html(balanceTotal.toFixed(2));
        }
    });
});

</script>
<style>
    .btn-custom {
        background-color: #007bff;
        color: white;
        border: none;
    }
</style>

