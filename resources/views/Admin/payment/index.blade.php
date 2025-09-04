@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex items-center justify-between bg- px-4  rounded-md shadow-sm" style="background-color: aliceblue; padding: 10px;" >
            <div class="d-flex align-items-center justify-content-between w-100">
                <h4 class="text-lg font-semibold text-gray-800 mb-0">Payment List</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success bg-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                    </button>
                    <button type="reset" id="resetFilter" class="btn btn-outline-danger bg-light">
                        <i class="fa fa-refresh"></i>
                    </button>
                </div>
            </div>
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

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-heade">
                <h5 class="modal-title text-dark" id="filterModalLabel">Filter Payments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="filterForm" method="GET">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" id="filterDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Customer</label>
                            <select name="guest_name" id="filterCustomer" class="form-select">
                                <option value="">-- Select Customer --</option>
                                @foreach($payments ?? [] as $payment)
                                    <option value="{{ $payment->guest_name ?? '' }}">{{ $payment->guest_name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Order Number</label>
                            <select name="order_id" id="filterOrder" class="form-select">
                                <option value="">-- Select Order --</option>
                                @foreach($payments ?? [] as $payment)
                                    <option value="{{ $payment->order_num ?? '' }}">{{ $payment->order_num ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" id="filterPaymentType" class="form-select">
                                <option value="">-- Select Payment Type --</option>
                                <option value="cash">Cash on Delivery</option>
                                <option value="upload_screenshot">Upload Screenshot Payment</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="filterStatus" class="form-select">
                                <option value="">-- Select Status --</option>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex justify-content-start align-items-end">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('payment.index') }}",
                type: "GET",
                data: function(d) {
                    // Add filter parameters
                    d.date = $('#filterDate').val();
                    d.guest_name = $('#filterCustomer').val();
                    d.order_id = $('#filterOrder').val();
                    d.payment_type = $('#filterPaymentType').val();
                    d.status = $('#filterStatus').val();
                }
            },
            order: [[0, 'desc']],
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'order_num', name: 'order_num' },
                { data: 'guest_name', name: 'guest_name' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'payment_type', name: 'payment_type'},
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

        // Handle filter form submission
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            
            // Reload the table with new filter parameters
            table.ajax.reload();
            
            // Close the modal
            $('#filterModal').modal('hide');
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Filter Applied!',
                text: 'Payments have been filtered according to your criteria.',
                timer: 1500,
                showConfirmButton: false
            });
        });
        
        // Handle reset filter button
        $('#resetFilter').on('click', function() {
            // Clear all filter fields
            $('#filterDate').val('');
            $('#filterCustomer').val('');
            $('#filterOrder').val('');
            $('#filterPaymentType').val('');
            $('#filterStatus').val('');
            
            // Reload the table without filters
            table.ajax.reload();
            
            // Show reset message
            Swal.fire({
                icon: 'info',
                title: 'Filters Reset!',
                text: 'All filters have been cleared and payments reloaded.',
                timer: 1500,
                showConfirmButton: false
            });
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

<style>
    .modal {
        z-index: 1060 !important;
    }
    .modal-backdrop {
        z-index: 1050 !important;
    }
    .w3-main, main {
        overflow: visible;
    }
</style>
