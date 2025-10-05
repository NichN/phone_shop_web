@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&display=swap" rel="stylesheet">

<!-- Styles for print -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="w3-main">
    <div class="container">
        <div class="card">
            <div class="card-body">

                <!-- Top Right Print Button (icon only) -->
                <div class="d-flex justify-content-end mb-3 no-print">
                    <button 
                        class="btn btn-outline-primary"
                        onclick="window.print()"
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                        title="Print Invoice"
                    >
                        <i class="fas fa-print"></i>
                    </button>
                </div>

                <!-- Invoice Header -->
                <form id="showInvoice">
                    <div class="row d-flex align-items-center justify-content-between">
                        <div style="display: flex; align-items: center; width: 100%;">
                            <!-- Logo -->
                            <img src="{{ asset('image/tay_meng_logo.jpg') }}" alt="Logo" 
                                style="border-radius: 50%; object-fit: cover; width: 80px; height: 80px; border: 3px solid gold;">

                            <!-- Title -->
                            <div style="flex: 1; text-align: center;">
                                <h3 style="margin: 0;">Purchase Invoice {{$purchase->reference_no}}</h3>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!-- Breadcrumb (Hidden on Print) -->
                    <div class="no-print" style="padding: 15px 25px; font-family: 'Hanuman', sans-serif; font-size: 14px;">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="background-color: transparent; padding: 0; margin-bottom: 10px;">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/dashboard') }}" style="color: #007bff; text-decoration: none;">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/purchase/add') }}" style="color: #007bff; text-decoration: none;">Purchase List</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('purchase.purchase_invoice', ['id' => $purchase->id]) }}" style="color: #007bff; text-decoration: none;">
                                        Invoice #{{ $purchase->reference_no }}
                                    </a>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Purchase Info -->
                    <div style="border: 1px solid #ccc; padding: 10px 15px; border-radius: 4px; background-color: #f9f9f9; display: flex; justify-content: space-between; align-items: center; max-width: 100%; margin-bottom: 15px;">
                        <div>
                            <strong style="color: #007bff;">Purchase Date:</strong> {{ $purchase->created_at }}<br><br>
                            <strong style="color: #007bff;">Supplier:</strong> {{ $purchase->supplier_name }}
                        </div>
                        <div>
                            <strong style="color: #007bff;">Status:</strong> {{ $purchase->payment_statuse }} <br>
                            <strong style="margin-left: 20px; color: #007bff;">Remark:</strong> {{ $purchase->note }}
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="background-color: #2e3b56; color: white;">Item Name</th>
                                <th style="background-color: #2e3b56; color: white;">Size</th>
                                <th style="background-color: #2e3b56; color: white;">Color</th>
                                <th style="background-color: #2e3b56; color: white;">Quantity</th>
                                <th style="background-color: #2e3b56; color: white;">Unit Price ($)</th>
                                <th style="background-color: #2e3b56; color: white;">Total ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->size }}</td>
                                    <td>{{ $item->color_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit_price }}</td>
                                    <td>{{ $item->subtotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

                <!-- Subtotal + QR -->
                <div style="border: 1px solid #ccc; border-radius: 4px; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center; background-color: #f9f9f9; max-width: 100%; margin-top: 15px;">
                    <img src="{{ asset('path/to/qr-code.png') }}" alt="QR Code" style="width: 100px; height: 100px;">
                    <div style="text-align: right;">
                        <strong style="color: #007bff;">Subtotal:</strong> {{ number_format($purchase->Grand_total, 2) }} $
                    </div>
                </div>

            </div>

            <!-- Add Payment Button (Hidden on Print) -->
            @if ($purchase->payment_statuse != 'Paid')
                <div class="d-flex justify-content-end mt-4 gap-2 mb-4 no-print">
                    <a href="{{ route('purchase.addpayment', ['id' => $purchase->id]) }}" class="btn btn-outline-success">
                        Add Payment
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
