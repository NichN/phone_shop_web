@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<div class="w3-main">
    <div class="container mt-4">
        <div class="w3-container" style="background-color: rgb(112, 0, 14) !important; color: white !important;height:70px;">
            <div style="display: flex; justify-content: space-between;">
                <h2 class="mt-3">TAYMENG PHONE SHOP</h2>
                <p>Welcome Back</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-header1">Total Products</div>
                    <div class="card-body">
                        <div class="stat-number">{{ $total_product->total_product ?? 0 }}</div>
                        <div class="stat-label"><b>Products Available</b></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-header1">Total Customers</div>
                    <div class="card-body">
                        <div class="stat-number">512</div>
                        <div class="stat-label"><b>Registered Users</b></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-header1">Total Sales</div>
                    <div class="card-body">
                        <div class="stat-number">$23,456</div>
                        <div class="stat-label"><b>This Month</b></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-header1">Total Invoice</div>
                    <div class="card-body">
                        <div class="stat-number">1</div>
                        <div class="stat-label"><b>This month</b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
