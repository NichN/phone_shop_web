@include('Admin.component.sidebar')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                <div class="mb-3 d-flex justify-content-end gap-2">
                        <button id="toggleFilter" class="btn btn-sm" style="background-color: #3adb83; color: white;">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('report.export_excel_supplier', ['id' => $supplier->id]) }}" class="btn btn-sm" style="background-color: #e966d7; color: white;" title="Export to Excel">
                            <i class="fas fa-file-excel"></i>
                        </a>

                    </div>
                    <div id="filterSection" class="justify-content-end" style="display: none;">
                       <form id="filterForm" class="row mb-3 g-2" action="{{ route('report.supplier_view', ['id' => $supplier->id]) }}" method="GET">
                        <!-- Start Date -->
                        <div class="col-md-2">
                            <label for="start_date">From Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>

                        <!-- End Date -->
                        <div class="col-md-2">
                            <label for="end_date">To Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>

                        <!-- Reference Number -->
                        <div class="col-md-2">
                            <label for="reference_no">Reference No</label>
                            <input type="text" name="reference_no" class="form-control" placeholder="e.g. PURCH001">
                        </div>
                        <!-- Payment Status -->
                        <div class="col-md-2">
                            <label for="payment_status">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">All</option>
                                <option value="Paid">Paid</option>
                                <option value="Partial">Partial</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" id="resetFilters" class="btn btn-sm btn-danger w-100" title="Reset Filters" style="margin-left: 5px;">
                                <i class="fas fa-sync-alt"></i>
                            </button>

                        </div>
                    </form>
                    </div>
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
                                    <th><input type="text" placeholder="Search Company"></th>
                                    <th><input type="text" placeholder="Search Phone"></th>
                                    <th><input type="text" placeholder="Search Email"></th>
                                    <th><input type="text" placeholder="Search Total Purchase"></th>
                                    <th id="total-amount">Total Amount</th>
                                    <th id="total-paid">Paid</th>
                                    <th id="total-balance">Balance</th>
                                    <th></th> <!-- No search input for Action -->
                                </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#toggleFilter').on('click', function () {
            $('#filterSection').slideToggle();
        });

        // Reload DataTable on filter submit
        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // Reset filters
        $('#resetFilters').on('click', function () {
            $('#filterForm')[0].reset();
            table.ajax.reload();
        });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
            url: "{{ route('report.supplier_view', ['id' => $supplier->id]) }}",
            data: function (d) {
                d.start_date = $('input[name=start_date]').val();
                d.end_date = $('input[name=end_date]').val();
                d.reference_no = $('input[name=reference_no]').val();
                d.payment_status = $('select[name=payment_status]').val();
                d.supplier_id = $('select[name=supplier_id]').val();
            }
        },

        order: [[0, 'desc']],
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
        drawCallback: function(settings) {
            let api = this.api();

            const parseNumber = (val) => {
                return typeof val === 'string' ? parseFloat(val.replace(/[^0-9.-]+/g, '')) : (parseFloat(val) || 0);
            };
            let totalAmount = api.column(4, { page: 'current' }).data().reduce((a, b) => a + parseNumber(b), 0);
            $('#total-amount').html('$' + totalAmount.toFixed(2));
            let totalPaid = api.column(5, { page: 'current' }).data().reduce((a, b) => a + parseNumber(b), 0);
            $('#total-paid').html('$' + totalPaid.toFixed(2));
            let totalBalance = api.column(6, { page: 'current' }).data().reduce((a, b) => a + parseNumber(b), 0);
            $('#total-balance').html('$' + totalBalance.toFixed(2));
        },
        initComplete: function () {
            var api = this.api();
            api.columns().every(function (index) {
                if (index < 4) {
                    var column = this;
                    var title = $(column.header()).text();
                    var input = $('<input type="text" placeholder="' + title + '" style="width: 100%; border: none;" />')
                        .appendTo($(column.footer()).empty())
                        .on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                }
            });
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

