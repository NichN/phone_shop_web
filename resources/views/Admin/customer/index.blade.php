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
                                <th>Date</th>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Action</th>
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