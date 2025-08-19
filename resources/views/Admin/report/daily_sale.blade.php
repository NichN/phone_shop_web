@include('Admin.component.sidebar')

<!-- CSS -->
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&display=swap" rel="stylesheet">

<div class="w3-main">
    <div class="container mt-5">
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
            <div class="container" style="z-index: 999999999999999 !important;">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header d-flex rounded justify-content-between align-items-center" style="background-color: #2e3b56;">
                            <h4 style="font-size: 1.2rem; font-weight: bold;">
                                <i class="fa fa-credit-card" aria-hidden="true"></i> Order Report
                            </h4>
                        </div>
                        <br>
                        <div class="felter" id="felter-report">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="customer_name">Customer Name</label>
                                    <input type="text" class="form-control" id="customer_name" placeholder="Enter Customer Name">
                                </div>
                                <div class="col-md-4">
                                    <label for="paid_by">Paid By</label>
                                    <select class="form-select" id="paid_by">
                                        <option value="">Select Payment Type</option>
                                        <option value="cash">Cash on Delivery</option>
                                        <option value="aba">Bank</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="inv_number">Order Number</label>
                                    <input type="text" class="form-control" id="inv_number" placeholder="Enter Invoice Number">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="payment_date">Order Date (Start)</label>
                                    <input type="date" class="form-control" id="payment_date">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date">Order Date (End)</label>
                                    <input type="date" class="form-control" id="end_date">
                                </div>
                                <div class="col-md-4">
                                    <label for="status">Order Status</label>
                                    <select class="form-select" id="status">
                                        <option value="">All Statuses</option>
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="canceled">Canceled</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-primary" id="submit_btn">Submit</button>
                        </div>
                        <br>
                        <div class="card-body">
                            <div style="overflow: auto;">
                                <table class="table table-bordered data-table" style="min-width: auto !important;">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">Order</th>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">Customer</th>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">Phone</th>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">Location</th>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">Amount</th>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">Paid by</th>
                                            <th style="background-color: #2e3b56 !important; color: white !important;">Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" style="text-align: right;">Total Amount:</th>
                                            <th id="total-amount"></th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    .card-header {
        background-color: #2e3b56;
    }
    .btn-custom {
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 14px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function(){
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
            dom: "<'row'<'col-md-6'l><'col-md-6 d-flex justify-content-end gap-2'B>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-md-5'i><'col-md-7'p>>",
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    className: 'btn btn-outline-dark btn-custom',
                    titleAttr: 'Print'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel"></i>',
                    className: 'btn btn-outline-success btn-custom',
                    titleAttr: 'Export to Excel'
                }
            ],
            order: [[0, 'desc']],
            columns:[
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'order_num', name: 'order_num'},
                {data: 'created_at', name: 'created_at'},
                {data: 'guest_name', name: 'guest_name'},
                {data: 'phone_guest', name: 'phone_guest'},
                {data: 'guest_address', name: 'guest_address'},
                {data: 'total_amount', name: 'total_amount'},
                {data: 'payment_type', name: 'payment_type'},
                {data: 'status', name: 'status'}
            ],
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();

                var intVal = function (i) {
                    return typeof i === 'string'
                        ? parseFloat(i.replace(/[\$,]/g, '')) || 0
                        : typeof i === 'number'
                        ? i : 0;
                };

                var totalAmount = api
                    .column(6, {page: 'current'})
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                $('#total-amount').html(totalAmount.toFixed(2));
            }
        });

        $('#submit_btn').on('click', function() {
            table.ajax.reload();
        });
    });
</script>
