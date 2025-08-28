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
                <h4 class="text-lg font-semibold text-gray-800 mb-0">Product Variant List</h4>
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
        <main style="padding:30px; gap:10px;" class="row">
            <div style="background-color: #87ceeb; padding: 10px; border-radius: 8px; width: 185px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Total Order</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: white; color: black; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_order ?? 0}}
                    </h2>
                    </div>
            </div> 
               <div style="background-color: #87ceeb; padding: 10px; border-radius: 8px; width: 185px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Pending Order</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: rgb(180, 180, 157) ; color: black; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_pending ?? 0}}
                    </h2>
                    </div>
            </div>
            <div style="background-color: #87ceeb; padding: 10px; border-radius: 8px; width: 185px;">
                <div>
                    <p style="margin: 0; font-weight: bold;">Canceled Order</p>
                    <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: #e65548; color: white; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_canceled ?? 0}}
                    </h2>
                </div>
            </div>
            <div style="background-color: #87ceeb; padding: 10px; border-radius: 8px; width: 185px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Processing Order</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: orange; color: white; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_processing ?? 0}}
                    </h2>
                        {{-- {{$totalprincipal?? 0}} --}}
                    </div>
            </div>
            <div style="background-color: #87ceeb; padding: 10px; border-radius: 8px; width: 185px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Completed Order</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; background-color: green ; color: white; padding: 10px; border-radius: 50%; width: 50px; height: 50px; line-height: 30px;">
                        {{$total_completed ?? 0}}
                    </h2>
                    </div>
            </div>
            <div style="background-color: #87ceeb; padding: 10px; border-radius: 8px; width: 185px;">
                    <div>
                        <p style="margin: 0; font-weight: bold;">Total Income</p>
                        <h2 id="total-income" style="margin: 20px auto 0; 
                            text-align: center; font-size: 20px; color: black; padding: 10px;"> USD
                        {{$total_income ?? 0}}
                    </h2>
                        {{-- {{ $due_status->due_count ?? 0 }} --}}
                    </div>
            </div>
        </main>
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-heade">
                <h5 class="modal-title text-dark" id="filterModalLabel">Filter Orders</h5>
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
                            @foreach($orders->unique('guest_name') as $order)
                                <option value="{{ $order->guest_name }}">{{ $order->guest_name }}</option>
                            @endforeach
                        </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Order Number</label>
                                                    <select name="order_id" id="filterOrder" class="form-select">
                            <option value="">-- Select Order --</option>
                            @foreach($orders->unique('order_num') as $order)
                                <option value="{{ $order->order_num }}">{{ $order->order_num }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Delivery Method</label>
                            <select name="delivery_method" id="filterDeliveryMethod" class="form-select">
                                <option value="">-- Select Delivery Method --</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->delivery_type  }}">{{ $order->delivery_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="filterStatus" class="form-select">
                                <option value="">-- Select Status --</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->status}}">{{ $order->status }}</option>
                                @endforeach
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
          <div style="display: flex; justify-content: space-between; align-items: center; background-color: aliceblue; padding: 10px;">
            <h4 style="margin: 0;">Monthly Order</h4>
            <div style="display: flex; align-items: center; gap: 10px;">
                <button onclick="prevMonth()" style="border: none; border-radius: 20px; padding: 5px 10px;">&#8592;</button>
                <span id="monthYearLabel" data-month="{{ now()->month }}" data-year="{{ now()->year }}">{{ now()->format('F Y') }}</span>
                <button onclick="nextMonth()" style="border: none; border-radius: 20px; padding: 5px 10px;">&#8594;</button>
            </div>            
        </div>
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
                                <th style="background-color: #2e3b56 !important; color: white !important;">Delivery</th>
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
@include('Admin.order.script')
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
