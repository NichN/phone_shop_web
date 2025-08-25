@include('Admin.component.sidebar')

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="w3-main">
    <a href="{{ route('report.delivery') }}" class="btn btn-secondary btn-sm mb-3">
        <i class="fa fa-arrow-left"></i> Back
    </a>

    <div class="container mt-2">
        <div class="card">
            <div class="card-header">
                <h6>Delivery Detail - {{ $status }} Orders</h6> 
            </div>
            <div class="card-body">
                <div style="overflow-x:auto;">
                    <table class="table table-bordered data-table text-center" id="orders-table">
                        <thead>
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Order</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Customer</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Phone</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Address</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Amount</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="5" style=" text-align:right; color: black !important;">Total:</th>
                                <th id="total-amount" style="background-color: #2e3b56 !important; color: white !important;"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

<script>
$(document).ready(function() {
    let status = "{{ $status }}";

    let table = $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('report.delivery.detail', $status) }}",
            type: 'GET',
            data: { status: status }
        },
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'order_num', name: 'order_num' },
            { data: 'guest_name', name: 'guest_name' },
            { data: 'phone_guest', name: 'phone_guest' },
            { data: 'guest_address', name: 'guest_address' },
            { data: 'delivery_fee', name: 'delivery_fee' },
            { data: 'status', name: 'status' },
        ],
        drawCallback: function(settings) {
            let api = this.api();

            let total = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    let x = typeof b === 'string' ? b.replace(/[^0-9.-]+/g,"") : b;
                    return a + parseFloat(x || 0);
                }, 0);

            $('#total-amount').html('$' + total.toFixed(2));
        }
    });
});
</script>
