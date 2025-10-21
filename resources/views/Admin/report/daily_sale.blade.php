@include('Admin.component.sidebar')

<!-- Styles -->
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&display=swap" rel="stylesheet">

<!-- Content -->
<div class="w3-main">
    <div class="container mt-5">
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
            <div class="container" style="z-index: 999999999999999 !important;">
                <div class="card">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #2e3b56;">
                            <h4 class="text-white mb-0">
                                <i class="fa fa-credit-card"></i> Order Report
                            </h4>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-2 my-3">
                            <button id="toggleFilter" class="btn btn-sm" style="background-color: #3adb83; color: white;">
                                <i class="fas fa-filter"></i>
                            </button>
                            <a href="{{ route('report.export_excel_dailysales', request()->query()) }}" 
                            class="btn btn-sm" 
                            style="background-color: #e966d7; color: white;" 
                            title="Export to Excel">
                                <i class="fas fa-file-excel"></i>
                            </a>

                        </div>
                        <div id="filterSection" class="mb-3" style="display: none;">
                            <form id="filterForm" class="row g-2">
                                <div class="col-md-4">
                                    <label for="customer_name">Customer Name</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer Name">
                                </div>
                                <div class="col-md-4">
                                    <label for="paid_by">Paid By</label>
                                    <select class="form-select" id="paid_by" name="paid_by">
                                        <option value="">Select Payment Type</option>
                                        <option value="cash">Cash on Delivery</option>
                                        <option value="aba">Upload Screenshot Payment</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="inv_number">Order Number</label>
                                    <input type="text" class="form-control" id="inv_number" name="inv_number" placeholder="Enter Invoice Number">
                                </div>

                                <div class="col-md-4">
                                    <label for="payment_date">From Date</label>
                                    <input type="date" class="form-control" id="payment_date" name="payment_date">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date">To Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                                <div class="col-md-4">
                                    <label for="status">Order Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All Statuses</option>
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="canceled">Canceled</option>
                                    </select>
                                </div>

                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button type="button" id="resetFilters" class="btn btn-sm btn-danger w-100 ms-2">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th style="background-color: #2e3b56; color: white;">No</th>
                                        <th style="background-color: #2e3b56; color: white;">Order</th>
                                        <th style="background-color: #2e3b56; color: white;">Date</th>
                                        <th style="background-color: #2e3b56; color: white;">Customer</th>
                                        <th style="background-color: #2e3b56; color: white;">Phone</th>
                                        <th style="background-color: #2e3b56; color: white;">Location</th>
                                        <th style="background-color: #2e3b56; color: white;">Amount</th>
                                        <th style="background-color: #2e3b56; color: white;">Paid by</th>
                                        <th style="background-color: #2e3b56; color: white;">Status</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th id="total-balance" style="background-color: #2e3b56; color: white;">Balance</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.card-header {
    background-color: #2e3b56;
}
.btn-custom {
    border-radius: 4px;
    padding: 8px 12px;
    font-size: 14px;
    border: 1px solid #28a745;
    background-color: transparent;
    color: #28a745;
    transition: all 0.3s ease;
}
.btn-custom:hover {
    background-color: #28a745;
    color: white;
}
.data-table th, .data-table td {
    vertical-align: middle;
}
</style>
<script>
$(document).ready(function () {
    $('#toggleFilter').on('click', function () {
        $('#filterSection').slideToggle();
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('report.daily_sale') }}",
            data: function (d) {
                d.guest_name = $('#customer_name').val();
                d.payment_type = $('#paid_by').val();
                d.order_num = $('#inv_number').val();
                d.order_date = $('#payment_date').val();
                d.end_date = $('#end_date').val();
                d.status = $('#status').val();
            }
        },
        order: [[0, 'desc']],
        columns: [
            {
                data: 'id',
                name: 'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'order_num', name: 'order_num' },
            {
                data: 'created_at',
                name: 'created_at',
                render: function (data) {
                    return data ? new Date(data).toLocaleDateString('en-GB') : '';
                }
            },
            { data: 'guest_name', name: 'guest_name' },
            { data: 'phone_guest', name: 'phone_guest' },
            { data: 'guest_address', name: 'guest_address' },
            {
                data: 'total_amount',
                name: 'total_amount',
                render: function (data) {
                    return data ? '$' + parseFloat(data).toFixed(2) : '$0.00';
                }
            },
            { data: 'payment_type', name: 'payment_type' },
            {
                data: 'status',
                name: 'status',
                render: function (data) {
                    let badgeClass = '';
                    switch (data.toLowerCase()) {
                        case 'canceled': badgeClass = 'bg-danger'; break;
                        case 'processing': badgeClass = 'bg-warning'; break;
                        case 'completed': badgeClass = 'bg-success'; break;
                        default: badgeClass = 'bg-secondary';
                    }
                    return `<span class="badge ${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            }
        ],
        drawCallback: function (settings) {
            let api = this.api();
            let totalBalance = api.column(6, { page: 'current' }).data().reduce(function (a, b) {
                let val = typeof b === 'string' ? parseFloat(b.replace(/[^0-9.-]+/g, '')) : b;
                return a + (val || 0);
            }, 0);
            $('#total-balance').html('$' + totalBalance.toFixed(2));
        },
        initComplete: function () {
            var api = this.api();
            api.columns().every(function (index) {
                if (index < 6) {
                    var column = this;
                    $('<input type="text" placeholder="' + $(column.header()).text() + '" style="width: 100%; border: none;" />')
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
    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        table.ajax.reload();
    });
    $('#resetFilters').on('click', function () {
        $('#filterForm')[0].reset();
        table.ajax.reload();
    });
});
</script>
