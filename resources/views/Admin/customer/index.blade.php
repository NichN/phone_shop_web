@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Customer List</h4>
        </div>
        @if(Auth::check() && Auth::user()->role_id == 1)
            <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Customer</button>
            </div>
        @endif    
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

<!-- Customer Details Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">
                    <i class="fas fa-user-circle me-2"></i>Customer Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Customer Profile Section -->
                    <div class="col-md-4">
                        <div class="text-center mb-3">
                            <div class="profile-image-container mb-3">
                                <img id="customerProfileImage" src="" alt="Profile" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover;" onerror="this.src='{{ asset('image/default-avatar.png') }}'">
                            </div>
                            <h6 id="customerName" class="mb-1"></h6>
                            <span id="customerRole" class="badge bg-primary"></span>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="card border-0" style="background-color: #f8f9fa;">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-3">Quick Stats</h6>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <h5 id="totalOrders" class="mb-0 text-primary">0</h5>
                                            <small class="text-muted">Orders</small>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="stat-item">
                                            <h5 id="totalSpent" class="mb-0 text-success">$0.00</h5>
                                            <small class="text-muted">Total Spent</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Information Section -->
                    <div class="col-md-8">
                        <div class="row">
                            <!-- Contact Information -->
                            <div class="col-12 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-address-book me-2"></i>Contact Information
                                </h6>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <strong>Email:</strong>
                                        <p id="customerEmail" class="mb-0 text-muted"></p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Phone:</strong>
                                        <p id="customerPhone" class="mb-0 text-muted"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Address Information -->
                            <div class="col-12 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Address Information
                                </h6>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <strong>Address Line 1:</strong>
                                        <p id="customerAddress1" class="mb-0 text-muted"></p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Address Line 2:</strong>
                                        <p id="customerAddress2" class="mb-0 text-muted"></p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>City:</strong>
                                        <p id="customerCity" class="mb-0 text-muted"></p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>State:</strong>
                                        <p id="customerState" class="mb-0 text-muted"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Account Information -->
                            <div class="col-12 mb-4">
                                <h6 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Account Information
                                </h6>
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <strong>Member Since:</strong>
                                        <p id="customerCreated" class="mb-0 text-muted"></p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Last Updated:</strong>
                                        <p id="customerUpdated" class="mb-0 text-muted"></p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Email Verified:</strong>
                                        <p id="customerEmailVerified" class="mb-0"></p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <strong>Last Order:</strong>
                                        <p id="lastOrderDate" class="mb-0 text-muted"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-shopping-bag me-2"></i>Recent Orders
                        </h6>
                        <div id="recentOrdersContainer">
                            <!-- Recent orders will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('Admin.customer.script')