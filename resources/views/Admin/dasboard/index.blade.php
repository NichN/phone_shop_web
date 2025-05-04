@include('Admin.component.sidebar')

<div class="w3-main">
    <div class="container mt-4">
        <div class="w3-container">
            <h1>Dashboard</h1>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-header">Total Products</div>
                    <div class="card-body">
                        <div class="stat-number">1,254</div>
                        <div class="stat-label">Products Available</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-header">Total Customers</div>
                    <div class="card-body">
                        <div class="stat-number">512</div>
                        <div class="stat-label">Registered Users</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-header">Total Sales</div>
                    <div class="card-body">
                        <div class="stat-number">$23,456</div>
                        <div class="stat-label">This Month</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-header">Total Invoice</div>
                    <div class="card-body">
                        <div class="stat-number">1</div>
                        <div class="stat-label">This month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
