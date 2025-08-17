@include('Admin.component.sidebar')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@include('Admin.component.sidebar')

<div class="w3-main">
    <div class="container mt-4">
        <div class="row g-3 mb-4">
            @foreach($cards as $card)
                <div class="col-md-2">
                    <div class="card text-center shadow-sm p-3">
                        <div class="mb-2">
                            <div class="bg-{{ $card['color'] }} text-white rounded-circle p-2 mx-auto" style="width: 40px;">
                                <i class="bi {{ $card['icon'] }}"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Total</p>
                        <h6 class="fw-bold text-primary">{{ $card['title'] }}</h6>
                        <h4>{{ $card['count'] }}</h4>
                    </div>
                </div>
            @endforeach

            <!-- Income vs Expense -->
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h6 class="fw-bold">Income Vs Expense</h6>
                    <div class="row text-center mt-3">
                        <div class="col-6 mb-2">
                            <small class="text-primary">Income Today</small>
                            <h5 class="text-primary">{{ $output }}</h5>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-info">Expense Today</small>
                            <h5 class="text-info">0$</h5>
                        </div>
                        <div class="col-6">
                            <small class="text-warning">Income This Month</small>
                            @foreach ($monthlyOutput as $month)
                            <span class="text-primary fw-bold"> ${{$month['total_income'] }}</span>
                        @endforeach
                        </div>
                        <div class="col-6">
                            <small class="text-danger">Expense This Month</small>
                            <h5 class="text-danger">-2,251$</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Invoices Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-heade d-flex justify-content-between align-items-center">
                <h6 class="mb-2 ml-2">Recent Invoices</h6>
                <a href="/order_dashboard" style="text-decoration: none; color: ">More...</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th style="text-align: center;">ORDER NUMBER</th>
                    <th style="text-align: center;">CUSTOMER</th>
                    <th style="text-align: center;">ISSUE DATE</th>
                    <th style="text-align: center;">AMOUNT</th>
                    <th style="text-align: center;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @if ($recentOrders->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">No orders found.</td>
                    </tr>
                @else
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td style="text-align: center;">{{ $order->order_num }}</td>
                            <td style="text-align: center;">{{ $order->guest_name }}</td>
                            <td style="text-align: center;">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                            <td style="text-align: center;">${{ number_format($order->total_amount, 2) }}</td>
                            <td style="text-align: center;">
                            <span class="badge @if($order->status === 'accepted' || $order->status === 'approved') bg-success-light
                                @elseif($order->status === 'processing') bg-info-light
                                @elseif($order->status === 'completed') bg-teal-light
                                @elseif($order->status === 'pending') bg-warning-light
                                @elseif($order->status === 'cancelled') bg-danger-light
                                @else bg-secondary-light @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
            </div>
        </div>
        <div class="card shadow-sm mb-4">
        <div class="card-heade d-flex justify-content-between align-items-center">
                <h6 class="mb-2 ml-2">Recent Purchases</h6>
                <a href="/purchase/add" style="text-decoration: none; color: ">More...</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th style="text-align: center;">BILL NUMBER</th>
                    <th style="text-align: center;">SUPPLIER</th>
                    <th style="text-align: center;">ISSUE DATE</th>
                    <th style="text-align: center;">AMOUNT</th>
                    <th style="text-align: center;">PAID</th>
                    <th style="text-align: center;">STATUS</th>
                </tr>
                </thead>
                <tbody>
                    @if ($recentBill->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">No bills found.</td>
                        </tr>
                    @else
                        @foreach ($recentBill as $bill)
                            <tr>
                                <td style="text-align: center;">{{ $bill->reference_no }}</td>
                                <td style="text-align: center;">{{ $bill->supplier_name }}</td>
                                <td style="text-align: center;">{{ \Carbon\Carbon::parse($bill->created_at)->format('M d, Y') }}</td>
                                <td style="text-align: center;">${{ number_format($bill->Grand_total, 2) }}</td>
                                <td style="text-align: center;">${{ number_format($bill->paid, 2) }}</td>
                                <td style="text-align: center;">
                                    <span class="badge @if($bill->payment_statuse === 'Paid') bg-success-light
                                        @elseif($bill->payment_statuse === 'Pending') bg-warning-light
                                        @elseif($bill->payment_statuse === 'Partially') bg-danger-light
                                        @else bg-secondary-light @endif">
                                        {{ $bill->payment_statuse }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
        <div class="row g-3">
    <!-- Invoices Section -->
    <div class="col-md-6">
        <div class="card shadow-sm p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <button class="btn btn-sm btn-primary" onclick="showSection('invoices', 'weekly')">Invoices Weekly</button>
                    <button class="btn btn-sm btn-light" onclick="showSection('invoices', 'monthly')">Invoices Monthly</button>
                </div>
            <div class="d-flex justify-content-end mb-2">
                <a href="/report/sale" class="btn btn-outline-primary">
                    Detail
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            </div>
            <div class="mt-3">
                <!-- Weekly Invoices -->
                <div id="invoices-weekly" class="data-section">
                    <p><strong>Total Generated:</strong>
                        @foreach ($weeklyOutput as $week)
                            <span class="text-primary fw-bold"> ${{$week['total_income'] }}</span>
                        @endforeach
                    </p>
                    <p><strong>Total Paid:</strong>
                        @foreach ($weeklyOutput_paid as $week_paid)
                            <span class="text-primary fw-bold"> ${{ $week_paid['total_income'] ?? 0}}</span>
                        @endforeach
                    </p>
                    <p><strong class="text-danger">Total Due:</strong>
                        @foreach ($weeklyOutput_cancel as $week_cancel)
                            <span class="text-primary fw-bold"> ${{ $week_cancel['total_income'] ?? 0}}</span>
                        @endforeach
                    </p>
                </div>

                <!-- Monthly Invoices -->
                <div id="invoices-monthly" class="data-section d-none">
                    <p><strong>Total Generated:</strong>
                        @foreach ($monthlyOutput as $month)
                            <span class="text-primary fw-bold"> ${{ $month['total_income'] }}</span>
                        @endforeach
                    </p>
                    <p><strong>Total Paid:</strong>
                        @foreach ($monthlyOutput_paid as $month_paid)
                            <span class="text-primary fw-bold"> ${{ $month_paid['total_income'] ?? 0}}</span>
                        @endforeach
                    </p>
                    <p><strong class="text-danger">Total Due:</strong>
                        @foreach ($monthlyOutput_cancel as $month_cancel)
                            <span class="text-primary fw-bold"> ${{ $month_cancel['total_income'] ?? 0}}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bills Section -->
    <div class="col-md-6">
        <div class="card shadow-sm p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <button class="btn btn-sm btn-primary" onclick="showSection('bills', 'weekly')">Bills Weekly</button>
                    <button class="btn btn-sm btn-light" onclick="showSection('bills', 'monthly')">Bills Monthly</button>
                </div>
                <div class="d-flex justify-content-end mb-2">
                <a href="/report/supplier" class="btn btn-outline-primary">
                    Detail
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            </div>
            <div class="mt-3">
                <!-- Weekly Bills -->
                <div id="bills-weekly" class="data-section">
                    <p><strong>Total Bill Generated:</strong>
                        @foreach ($billOutput as $bill)
                            <span class="text-primary fw-bold"> ${{ $bill['total'] ?? 0}}</span>
                        @endforeach
                    </p>
                    <p><strong>Total Balance:</strong>
                        @foreach ($billOutputpaid as $bill)
                            <span class="text-primary fw-bold"> ${{ $bill['total'] ?? 0}}</span>
                        @endforeach
                    </p>
                    <p><strong class="text-danger">Total Unpaid:</strong>
                            <span class="text-danger fw-bold"> ${{ $unpaidBills['total_sum'] ?? 0 }}</span>
                    </p>
                </div>

                <!-- Monthly Bills -->
                <div id="bills-monthly" class="data-section d-none">
                    <p><strong>Total Bill Generated:</strong>
                            <span class="text-primary fw-bold"> ${{ $formatted_total}}</span>
                    </p>
                    <p><strong>Total Balance:</strong>
                        @foreach ($billOutputbalance as $bill)
                            <span class="text-primary fw-bold"> ${{ $bill['total'] ?? 0}}</span>
                        @endforeach
                    </p>
                    <p><strong class="text-danger">Total Due:</strong>
                        @foreach ($billOutputcancel as $bill)
                            <span class="text-danger fw-bold"> ${{ $bill['total'] ?? 0}}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function showSection(type, range) {
        // Toggle the data sections
        const weekly = document.getElementById(`${type}-weekly`);
        const monthly = document.getElementById(`${type}-monthly`);
        
        if (range === 'weekly') {
            weekly.classList.remove('d-none');
            monthly.classList.add('d-none');
        } else {
            weekly.classList.add('d-none');
            monthly.classList.remove('d-none');
        }
        
        // Toggle the button states
        const buttons = document.querySelectorAll(`[onclick^="showSection('${type}'"]`);
        buttons.forEach(button => {
            if (button.textContent.toLowerCase().includes(range)) {
                button.classList.remove('btn-light');
                button.classList.add('btn-primary');
            } else {
                button.classList.remove('btn-primary');
                button.classList.add('btn-light');
            }
        });
    }
</script>
<style>
    .bg-success-light {
        background-color: #d4edda;
        color: #155724;
    }
    .bg-info-light {
        background-color: #cce5ff;
        color: #004085;
    }
    .bg-teal-light {
        background-color: #e6f3f3;
        color: #0c5460;
    }
    .bg-warning-light {
        background-color: #fff3cd;
        color: #856404;
    }
    .bg-danger-light {
        background-color: #f8d7da;
        color: #721c24;
    }
    .bg-secondary-light {
        background-color: #e2e3e5;
        color: #383d41;
    }
</style>
