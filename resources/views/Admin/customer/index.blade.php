@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Customer List</h4>
        </div>
        <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
        </div>    
        <div class="container no-print">
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3">
                        <thead style="text-align:center;">
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Name</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Email</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Phone</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Address</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>  
                        <tbody></tbody>                              
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Admin.customer.script')