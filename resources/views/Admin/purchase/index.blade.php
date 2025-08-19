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
        <div class="flex justify-between">
            <h4 class="card-header">Purchase List</h4>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{route('purchase.index')}}">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>
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
</style>
@include('Admin.purchase.script')