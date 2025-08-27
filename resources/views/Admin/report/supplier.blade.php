@include('Admin.component.sidebar')

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<div class="w3-main">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="card-header d-flex rounded justify-content-between align-items-center" style="background-color: #2e3b56;">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">
                        <i class="fa fa-product-hunt" aria-hidden="true"></i> Supplier Report
                    </h4>
                </div>

                <div class="card-body">
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered data-table text-center">
                            <thead>
                                <tr>
                                    <th style="background-color: #2e3b56; color: white;">Company</th>
                                    <th style="background-color: #2e3b56; color: white;">Phone</th>
                                    <th style="background-color: #2e3b56; color: white;">Email</th>
                                    <th style="background-color: #2e3b56; color: white;">Total Purchase</th>
                                    <th style="background-color: #2e3b56; color: white;">Total Amount</th>
                                    <th style="background-color: #2e3b56; color: white;">Paid</th>
                                    <th style="background-color: #2e3b56; color: white;">Balance</th>
                                    <th style="background-color: #2e3b56; color: white;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th><input type="text" placeholder="Search Company"></th>
                                    <th><input type="text" placeholder="Search Phone"></th>
                                    <th><input type="text" placeholder="Search Email"></th>
                                    <th><input type="text" placeholder="Search Total Purchase"></th>
                                    <th id="total-amount" style="background-color: #2e3b56; color: white;">Total Amount</th>
                                    <th id="total-paid" style="background-color: #2e3b56; color: white;">Paid</th>
                                    <th id="total-balance" style="background-color: #2e3b56; color: white;">Balance</th>
                                    <th></th> <!-- No search input for Action -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Setup -->
<script>
$(document).ready(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('report.supplier') }}",
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load supplier data. Please try again.'
                });
            }
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'total_purchases', name: 'total_purchases' },
            { data: 'total_Grand_total', name: 'total_Grand_total' },
            { data: 'total_paid', name: 'total_paid' },
            { data: 'total_balance', name: 'total_balance' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
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

<!-- Styling for footer inputs -->
<style>
    tfoot input {
        width: 100%;
        padding: 4px;
        box-sizing: border-box;
        font-size: 0.85rem;
    }
</style>