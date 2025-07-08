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
        
        {{-- <div class="container no-print mt-2">
            <div class="dropdown mb-3">
        <button class="btn btn-outline-secondary dropdown-toggle d-flex justify-content-end" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="ms-auto">Filter: <span id="selectedFilterLabel">All</span></span>
        </button>

        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
            <li>
                <label class="dropdown-item">
                    <input class="form-check-input me-2 filter-radio" type="radio" name="filterOption" value="today">
                    Today
                </label>
            </li>
            <li>
                <label class="dropdown-item">
                    <input class="form-check-input me-2 filter-radio" type="radio" name="filterOption" value="month">
                    This Month
                </label>
            </li>
            <li>
                <label class="dropdown-item">
                    <input class="form-check-input me-2 filter-radio" type="radio" name="filterOption" value="year">
                    This Year
                </label>
            </li>
            <li>
                <label class="dropdown-item">
                    <input class="form-check-input me-2 filter-radio" type="radio" name="filterOption" value="all" checked>
                    All
                </label>
            </li>
        </ul>
    </div> --}}
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3">
                        <thead style="text-align:center;">
                            <tr>
                                <th>Date</th>
                                <th>Order</th>
                                <th>Cutomer</th>
                                <th>Amount</th>
                                <th>Payment Type</th>
                                <th>Remark</th>
                                <th>Status</th>
                                {{-- <th>Delivery</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>                                
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Admin.payment.script')
