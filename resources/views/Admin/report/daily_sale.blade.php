@include('Admin.component.sidebar')

<!-- CSS -->
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- Google Fonts (Battambang) -->
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
                                    <input type="text" class="form-control" id="customer_name" name="guest_name" placeholder="Enter Customer Name">
                                </div>
                                <div class="col-md-4">
                                    <label for="paid_by">Paid By</label>
                                    <select class="form-select" id="paid_by" name="payment_type">
                                        <option value="">Select Payment Type</option>
                                        <option value="cash">cash on delivery</option>
                                        <option value="aba">Bank</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="inv_number">Order Number</label>
                                    <input type="text" class="form-control" id="inv_number" name="order_num" placeholder="Enter Invoice Number">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="payment_date">Order Date</label>
                                    <input type="date" class="form-control" id="payment_date" name="order_date">
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
                                            <th>No</th>
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Order Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        
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
</style>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert -->
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
                }
            },
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
                {data: 'total_amount', name: 'total_amount'},
                {data: 'payment_type', name: 'payment_type'},
                {data: 'status', name: 'status'}
            ]
        });

        // When clicking "Submit", reload table with new filters
        $('#submit_btn').on('click', function() {
            table.ajax.reload();
        });
    });
</script>

