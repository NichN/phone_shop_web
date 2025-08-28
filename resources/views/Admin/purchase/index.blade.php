@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&display=swap" rel="stylesheet">


<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex items-center justify-between bg- px-4  rounded-md shadow-sm" style="background-color: aliceblue; padding: 10px; margin: 0 0 20px 0;" >
            <div class="d-flex align-items-center justify-content-between w-100">
                <h4 class="text-lg font-semibold text-gray-800 mb-0">Purchase List</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success bg-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                    </button>
                    <button type="reset" id="resetFilter" class="btn btn-outline-danger bg-light">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <a href="{{route('purchase.index')}}">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-2"></i>
                        </button> 
                    </a>
                </div>
            </div>
        </div>   

        <div class="container no-print" style="z-index: 999999999999999 !important;">
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table payment mt-3" style="min-width: auto !important;">
                        <thead>
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Reference No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Grand Total</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Paid</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Balance</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Status</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>                                
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    @media print {
        body * {
            visibility: hidden;
            font-family: 'Moul', cursive;
        }
        #invoiceModal, #invoiceModal * {
            visibility: visible;
        }
        #invoiceModal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .modal-dialog {
            max-width: 100%;
            margin: 0;
        }
        .no-print {
            display: none !important;
        }
    }
    
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

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-heade">
                <h5 class="modal-title text-dark" id="filterModalLabel">Filter Purchase Records</h5>
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
                            <label class="form-label">Reference No</label>
                            <select name="reference_no" id="filterReferenceNo" class="form-select">
                                <option value="">-- Select Reference --</option>
                                @foreach($purchases ?? [] as $purchase)
                                    <option value="{{ $purchase->reference_no ?? '' }}">{{ $purchase->reference_no ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Grand Total Range</label>
                            <select name="grand_total_range" id="filterGrandTotalRange" class="form-select">
                                <option value="">-- Select Range --</option>
                                <option value="0-10000">$0 - $10,000</option>
                                <option value="10000-50000">$10,000 - $50,000</option>
                                <option value="50000-100000">$50,000 - $100,000</option>
                                <option value="100000+">$100,000+</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" id="filterPaymentStatus" class="form-select">
                                <option value="">-- Select Status --</option>
                                <option value="Paid">Paid</option>
                                <option value="Partially">Partially Paid</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Balance Range</label>
                            <select name="balance_range" id="filterBalanceRange" class="form-select">
                                <option value="">-- Select Range --</option>
                                <option value="0">$0 (Fully Paid)</option>
                                <option value="1-1000">$1 - $1,000</option>
                                <option value="1000-10000">$1,000 - $10,000</option>
                                <option value="10000+">$10,000+</option>
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

@include('Admin.purchase.script')