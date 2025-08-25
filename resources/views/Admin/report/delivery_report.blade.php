@include('Admin.component.sidebar')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<div class="w3-main">
   <div class="container mt-5">
    <div class="row">
        <div class="col-md-12 text-center mb-4">
            <h2>📦 Delivery Report</h2>
            <p class="text-muted">Overview of delivery fees by status</p>
        </div>
    </div>

    <div class="row">
        <!-- Completed Deliveries -->
        <div class="col-md-6">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <h4 class="card-title text-success">Completed Orders</h4>
                    <h3>{{ number_format($total_feeCompleted, 2) }} $</h3>
                </div>
            </div>
        </div>

        <!-- Pending Deliveries -->
        <div class="col-md-6">
            <div class="card shadow-sm border-warning">
                <div class="card-body text-center">
                    <h4 class="card-title text-warning text-center">Processing Orders</h4>
                    <h3>{{ number_format($total_feePending, 2) }} $</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional Table -->
    <div class="row mt-5">
        <div class="col-md-12">
            <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Status</th>
            <th>Total Delivery Fee</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span class="badge bg-success text-center">Completed</span></td>
            <td>{{ number_format($total_feeCompleted, 2) }} $</td>
            <td>
                <div class="d-flex justify-content-center">
                    <a href="{{ route('report.delivery.detail', ['status' => 'completed']) }}" class="btn btn-outline-primary btn-sm">
                        View Details
                    </a>
                </div>
                   
            </td>
        </tr>
        <tr>
            <td><span class="badge bg-warning text-dark">Processing</span></td>
            <td>{{ number_format($total_feePending, 2) }} $</td>
            <td>
                <div class="d-flex justify-content-center">
                <a href="{{ route('report.delivery.detail', ['status' => 'processing']) }}" class="btn btn-outline-primary btn-sm">
                    View Details
                </a>
                </div>
            </td>
        </tr>
    </tbody>
</table>

        </div>
    </div>
</div>

</div>