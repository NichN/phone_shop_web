@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <main style="padding:10px; gap:10px;" class="row">
            <div style="background-color: #f8e4a2; padding: 10px; border-radius: 8px; width: 180px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Total Order</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: white; color: black; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_order ?? 0}}
                    </h2>
                    </div>
            </div> 
            <div style="background-color: #f8e4a2; padding: 10px; border-radius: 8px; width: 180px;">
                <div>
                    <p style="margin: 0; font-weight: bold;">Canceled Order</p>
                    <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: #e65548; color: white; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_canceled ?? 0}}
                    </h2>
                </div>
            </div>
            <div style="background-color: #f8e4a2; padding: 10px; border-radius: 8px; width: 180px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Processing Order</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: orange; color: white; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_processing ?? 0}}
                    </h2>
                        {{-- {{$totalprincipal?? 0}} --}}
                    </div>
            </div>
            <div style="background-color: #f8e4a2; padding: 10px; border-radius: 8px; width: 180px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Completed Order</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: green ; color: white; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_completed ?? 0}}
                    </h2>
                    </div>
            </div>
            <div style="background-color: #f8e4a2; padding: 10px; border-radius: 8px; width: 180px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Total Fee (Pending)</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; color: black; padding: 10px;"> USD
                        {{$total_feepending ?? 0}}
                    </h2>
                        {{-- {{ $due_status->due_count ?? 0 }} --}}
                    </div>
            </div>
            <div style="background-color: #f8e4a2; padding: 10px; border-radius: 8px; width: 180px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Total Fee Collection</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; color: black; padding: 10px;"> USD
                        {{$total_fee ?? 0}}
                    </h2>
                        {{-- {{ $due_status->due_count ?? 0 }} --}}
                    </div>
            </div>
        </main>
        <div class="container no-print">
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3">
                        <thead style="text-align:center;">
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Order</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Cutomer</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Phone</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Address</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Amount</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Status</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>                                
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Admin.delivery.script')