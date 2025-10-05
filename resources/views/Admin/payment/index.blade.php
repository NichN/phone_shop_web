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
                {{-- <div class="d-flex gap-2">
                    <button class="btn btn-outline-success bg-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                    </button>
                    <button type="reset" id="resetFilter" class="btn btn-outline-danger bg-light">
                        <i class="fa fa-refresh"></i>
                    </button>
                </div> --}}
            </div>
        </div> 
        <div class="d-flex gap-2 justify-content-end mt-3">
            <div class="d-flex gap-2">
                    <button class="btn btn-outline-success bg-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                    </button>
                    <button type="reset" id="resetFilter" class="btn btn-outline-danger bg-light">
                        <i class="fa fa-refresh"></i>
                    </button>
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

@include('Admin.payment.script')

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
