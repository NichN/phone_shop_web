@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Purchase List</h4>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{route('purchase.index')}}">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i> Add Purchases
            </button> 
            </a>
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
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #2e3b56;">
                <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Purchase Invoice</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPr">
                    @method('GET')
                    @csrf
                    <div class="row mb-4">
                        <input type="hidden" id="pro_dt" name="id">
                        <div class="col-md-6">
                            <p>ឈ្មោះអ្នកផ្គត់ផ្គង់ : <span></span></p>
                            <p><strong>Reference :</strong> <span></span></p>
                            <p><strong>កាលបរិច្ឆេទ:</strong> <span></span></p>
                        </div>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">ផលិតផល</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">ពណ៌</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">ទំហំ</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">ចំនួន</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">តម្លៃ</th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;">សរុប</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>សរុបទាំងអស់:</strong></td>
                                    <td id="invoiceSubtotal" style="background-color: #2e3b56; color:white;">$0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="mb-3">
                        <p id="invoiceNotes"><b>Thank you for your business!</b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('Admin.purchase.script')