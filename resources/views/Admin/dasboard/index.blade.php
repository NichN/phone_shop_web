@include('Admin.component.sidebar')

<div class="w3-main">
    <div class="container mt-4">
        <div class="w3-container">
            <div class="mb-4">
                <h1 class="fw-bold mb-2">Dashboard</h1>
                <p class="text-muted mb-0">Welcome back! Here's what's happening with your store today.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 150px; background-color:#bdfff9 ;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color:white;">
                                <i class="bi bi-box-seam text-primary fs-4"></i>
                            </div>
                            <div class="text-center text-md-start">
                                <div class="small mb-1 text-muted">Total Products</div>
                                <h3 class="fw-bold mb-0">{{$total_product}}</h3>
                                {{-- <span class="badge bg-success bg-opacity-10 text-success small">+12%</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 150px; background-color:#bdfff9 ;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color:white;">
                                <i class="bi bi-people text-primary fs-4"></i>
                            </div>
                            <div class="text-center text-md-start">
                                <div class="small mb-1 text-muted" style="color: black;"><b>Total Customers</b></div>
                                <h3 class="fw-bold mb-0">{{$total_customer}}</h3>
                                {{-- <span class="badge bg-success bg-opacity-10 text-success small">+8%</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 150px; background-color:#bdfff9 ;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color:white;">
                                <i class="bi bi-currency-dollar text-warning fs-4"></i>
                            </div>
                            <div class="text-center text-md-start">
                                <div class="small mb-1 text-muted">Total purchase</div>
                               <h3 class="fw-bold mb-0">{{ number_format((float)$Grand_total, 0) }} $</h3>
                                {{-- <span class="badge bg-success bg-opacity-10 text-success small">+24%</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 150px; background-color:#bdfff9 ;">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color:white;">
                                <i class="bi bi-receipt text-danger fs-4"></i>
                            </div>
                            <div class="text-center text-md-start">
                                <div class="small mb-1 text-muted">Total Orders</div>
                                <h3 class="fw-bold mb-0">1,842</h3>
                                {{-- <span class="badge bg-success bg-opacity-10 text-success small">+5%</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;">Stock & Purchase</h5>
                        <div class="d-flex gap-2">
                            <select id="yearSelect" class="form-select form-select-sm" style="width: 90px;">
                                <option value="2023">2023</option>
                                <option value="2024" selected>2024</option>
                                <option value="2025">2025</option>
                            </select>
                            <select id="monthSelect" class="form-select form-select-sm" style="width: 120px;">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6" selected>June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="stockPieChart"></canvas>
                        </div>
                        <div class="mt-3 d-flex justify-content-center gap-4">
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="d-inline-block rounded-circle"
                                        style="width: 12px; height: 12px; background-color: #3b82f6;"></span>
                                    <span class="small">In Stock</span>
                                </div>
                                <div class="fw-bold">1,024 items</div>
                            </div>
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="d-inline-block rounded-circle"
                                        style="width: 12px; height: 12px; background-color: #93c5fd;"></span>
                                    <span class="small">Low Stock</span>
                                </div>
                                <div class="fw-bold">156 items</div>
                            </div>
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="d-inline-block rounded-circle"
                                        style="width: 12px; height: 12px; background-color: #bfdbfe;"></span>
                                    <span class="small">Out of Stock</span>
                                </div>
                                <div class="fw-bold">74 items</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;">Monthly Sale</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="salesBarChart"></canvas>
                        </div>
                        <div class="mt-3 d-flex justify-content-center gap-4">
                            <div class="text-center">
                                <div class="small text-muted">Today</div>
                                <div class="fw-bold text-success">$1,245</div>
                            </div>
                            <div class="text-center">
                                <div class="small text-muted">This Week</div>
                                <div class="fw-bold">$8,752</div>
                            </div>
                            <div class="text-center">
                                <div class="small text-muted">This Month</div>
                                <div class="fw-bold">$23,456</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Admin.dasboard.script')