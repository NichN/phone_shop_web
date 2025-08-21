@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between" style="background-color: aliceblue; padding: 10px;">
            <h4>Payment List</h4>
        </div>    
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3">
                        <thead style="text-align:center;">
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Order</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Cutomer</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Amount</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Payment Type</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Remark</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Status</th>
                                {{-- <th style="background-color: #2e3b56 !important; color: white !important;">Delivery</th> --}}
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>                                
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 35vw;">
        <div class="modal-content p-3">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="invoiceContent">
                <!-- Dynamic invoice content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('payment.index') }}",
            order: [[0, 'desc']],
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'order_num', name: 'order_num' },
                { data: 'guest_name', name: 'guest_name' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'payment_type', name: 'payment_type' },
                { data: 'remark', name: 'remark' },
                {
                    data: 'payment_status',
                    name: 'payment_status',
                    render: function (data) {
                        let badgeClass = '';
                        const status = data ? data.toLowerCase() : '';

                        switch (status) {
                            case 'paid':
                                badgeClass = 'background-color: #d4edda; color: #155724;';
                                break;
                            case 'pending':
                                badgeClass = 'background-color: #fff3cd; color: #856404;';
                                break;
                            default:
                                badgeClass = 'background-color: #e2e3e5; color: #6c757d;';
                        }

                        return `<span style="padding: 5px 10px; border-radius: 5px; font-weight: 500; ${badgeClass}">${data || 'Unknown'}</span>`;
                    }
                },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        // Handle "View Invoice" button click
        $('.data-table').on('click', '.view-invoice', function () {
            const orderId = $(this).data('id');
            const modalElement = document.getElementById('invoiceModal');

            if (!modalElement) {
                console.error("Modal element with ID 'invoiceModal' not found.");
                return;
            }

            $('#invoiceContent').html('<div class="text-center">Loading...</div>');

            const invoiceModal = new bootstrap.Modal(modalElement);
            invoiceModal.show();

            $.ajax({
                url: `/payment/order_detail/${orderId}`,
                method: 'GET',
                success: function (response) {
                    $('#invoiceContent').html(response);
                },
                error: function () {
                    $('#invoiceContent').html('<div class="text-danger">Failed to load invoice.</div>');
                }
            });
        });
    });

    // Print invoice
    function printInvoice() {
        var printContents = document.getElementById('invoiceContent').innerHTML;
        var win = window.open('', '', 'height=700,width=900');
        win.document.write('<html><head><title>Invoice</title>');
        win.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
        win.document.write('</head><body>');
        win.document.write(printContents);
        win.document.write('</body></html>');
        win.document.close();
        win.focus();
        win.print();
        win.close();
    }
</script>
