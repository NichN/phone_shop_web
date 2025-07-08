@include('Admin.component.sidebar')

<div class="w3-main">
    <div class="container mt-4">
        <div class="w3-container">
             <div class="row">
                <div class="col-12 mb-4 d-flex justify-content-between align-items-center welcome-message">
                    <span>
                        Welcome back,  {{ Str::limit(Auth::user()->name, 18) }}
                    </span>
                     <div class="position-relative" style="width: 60px; height: 60px;">
                        <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('image/smphone.png') }}" class="rounded-circle shadow" style="width: 56px; height: 56px; object-fit: cover; border: 2px solid #007bff; box-shadow: 0 2px 8px rgba(0,0,0,0.12);  border : 2px solid rgb(39, 67, 158);">
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card shadow-sm" style="border-radius: 12px; height: 150px;  box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);   border : 2px solid rgb(39, 67, 158);">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color:rgb(233, 169, 116);">
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
                 <div class="card shadow-sm" style="border-radius: 12px; height: 150px;  box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);   border : 2px solid rgb(39, 67, 158);">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-people text-white fs-4"></i>
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
                 <div class="card shadow-sm" style="border-radius: 12px; height: 150px;  box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);   border : 2px solid rgb(39, 67, 158);">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color:pink">
                                <i class="bi bi-currency-dollar text-success fs-4"></i>
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
                 <div class="card shadow-sm" style="border-radius: 12px; height: 150px;  box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);   border : 2px solid rgb(39, 67, 158);">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color: green;">
                                <i class="bi bi-receipt text-white fs-4"></i>
                            </div>
                            <div class="text-center text-md-start">
                                <div class="small mb-1 text-muted">Total Orders</div>
                                <h3 class="fw-bold mb-0">{{$total_order}}</h3>
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
                    <div class="card-header bg-light border-0 d-flex align-items-center justify-content-between py-3">
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;">Stock</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 200px;">
                            <canvas id="stockPieChart"></canvas>
                        </div>
                        <div class="mt-3 d-flex justify-content-center gap-4">
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="d-inline-block rounded-circle"
                                        style="width: 12px; height: 12px; background-color: #0ea5e9;"></span>
                                    <span class="small">In Stock</span>
                                </div>
                                <div class="fw-bold">{{ $product_instock }}</div>
                            </div>
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="d-inline-block rounded-circle"
                                        style="width: 12px; height: 12px; background-color: #ec4899;"></span>
                                    <span class="small">Out of Stock</span>
                                </div>
                                <div class="fw-bold">{{ $soldOutItems }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background: #fefefe;">
                        <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.3rem;">
                                <i class="bi bi-clock-history me-2 text-primary"></i>Recent Orders
                            </h5>
                            <a href="/order_dashboard" class="text-decoration-none small text-primary">View all</a>
                        </div>
                        <div class="card-body">
                            @forelse ($recentOrders as $order)
                                <div class="d-flex align-items-center mb-4 p-2 rounded hover-shadow" style="background-color: #f8f9fa;">
                                    {{-- User Profile Image --}}
                                    <img src="{{ $order->profile_image 
                                        ? asset('storage/' . $order->profile_image) 
                                        : asset('image/smphone.png') }}"
                                        alt="Profile" class="rounded-circle shadow-sm"
                                        style="width: 50px; height: 50px; object-fit: cover;">

                                    {{-- Guest Name and Order Info --}}
                                    <div class="ms-3 flex-grow-1">
                                        <div class="fw-semibold text-dark">{{ $order->guest_name }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</small>
                                    </div>

                                    {{-- Order Total --}}
                                    <div class="fw-bold text-dark">${{ number_format($order->total_amount, 2) }}</div>
                                </div>
                            @empty
                                <p class="text-muted">No recent orders found.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
        </div>
    </div>
    <div style="display: flex; justify-content:space-between;" class="gap-3">
        <div class="col-md-6 mt-3">
        <div class="card border-0 shadow-sm h-300" style="border-radius: 12px;">
            <div class="card-header bg-light border-0 d-flex align-items-center justify-content-between py-3">
                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;">Orders Monthly (completed)</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="ordersBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="card border-0 shadow-sm h-300" style="border-radius: 12px;">
            <div class="card-header bg-light border-0 d-flex align-items-center justify-content-between py-3">
                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;">Orders processing</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="pro_orderBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@include('Admin.dasboard.script')
<style>
    .welcome-message{
        color: black;
        background-color: rgb(236, 184, 141);
        text-align: center;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
        font-weight: 700;
}
</style>