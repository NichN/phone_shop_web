@extends('Layout.headerfooter')

@section('title', 'Order Details')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
    <!-- Include Bootstrap 5 if not already included -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <a href="/checkout/history" style="text-decoration: none;">← Back</a>
        <h4 class="text-center mb-4">
            Order Details <i class="fas fa-history text-primary"></i>
        </h4>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <p class="mb-0">
                <strong>Ordered On:</strong> {{ $order->created_at->format('d-m-Y') }}<br>
                <strong>Order:</strong> #{{ $order->order_num }}
            </p>

            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#invoiceModal">
                View Invoice
            </button>
        </div>

        <!-- Main Content: Summary & Products -->
        <div class="d-flex flex-wrap" style="width: 100%;">
            <!-- Order Summary (Left) -->
            <div class="me-3" style="flex: 1 1 300px; max-width: 350px;">
                <div class="card p-3 mb-3">
                    <p><strong>Delivery Address</strong></p>
                    <p>{{ $order->guest_address ?? ($order->address ?? '-') }}</p>

                    <p><strong>Payment Method</strong></p>
                    <p>{{ $payment->payment_type ?? 'N/A' }}</p>

                    <p><strong>Order Summary</strong></p>
                    <p>Item Quantity: {{ $orderItems->sum('quantity') }}</p>
                    <p>Subtotal: <span style="color:blue;">${{ number_format($order->subtotal, 2) }}</span></p>
                    <p>Delivery Fee: ${{ number_format($delivery->fee ?? 0, 2) }}</p>
                    <p>Grand Total: ${{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <!-- Products (Right) -->
            <div style="flex: 2 1 600px;">
                <div class="bg-light p-3 mb-3 rounded shadow-sm">
                    @if (isset($orderItems) && $orderItems->isNotEmpty())
                        @foreach ($orderItems as $item)
                            {{-- @php
                                $images = json_decode($item->imgSrc, true);
                                $imgSrc = $images && count($images) > 0 ? $images[0] : 'default-image.jpg';
                                $title = $item->product_name ?? 'Unknown Product';
                                $color = $item->color_code ?? '#ccc';
                                $size = $item->size ?? 'N/A';
                                $quantity = $item->quantity ?? 0;
                                $price = $item->price ?? '0.00';
                            @endphp --}}
                            @php
                                $decodedImages = json_decode($item->imgSrc, true);
                                $images = is_array($decodedImages) ? $decodedImages : [];

                                $imgSrc = count($images) > 0 ? $images[0] : 'default-image.jpg';

                                $title = $item->title ?? 'Unknown Product';
                                $color = $item->color_code ?? '#ccc';
                                $size = $item->size ?? 'N/A';
                                $quantity = $item->quantity ?? 0;
                                $price = $item->price ?? '0.00';
                                $subtotal = $item->subtotal ?? '0.00';
                                $deliveryFee = $item->fee ?? '0.00';
                                $totalAmount = ($subtotal + $deliveryFee);
                            @endphp

                            <div class="d-flex mb-3 p-2 rounded" style="border:1px solid #b35dae; align-items: flex-start;">
                            <!-- Product Image -->
                            <img src="{{ asset('storage/' . $imgSrc) }}" alt="{{ $title }}" width="120"
                                height="120" class="rounded" style="object-fit: cover;">

                            <!-- Product Info -->
                            <div class="ms-3 flex-grow-1">
                                <!-- Top line: Product Name left, Price right -->
                                <div class="d-flex justify-content-between w-100">
                                    <div><strong>Product Name:</strong> {{ $title }}</div>
                                    <div class="text-danger"><strong>Price:</strong> ${{ $price }}</div>
                                </div>

                                <!-- Other details below -->
                                <div><strong>Color:</strong>
                                    <span style="display:inline-block; width:20px; height:20px; background-color: {{ $color }}; border: 1px solid #ccc;"></span>
                                </div>
                                <div><strong>Size:</strong> {{ $size }}</div>
                                <div><strong>Quantity:</strong> {{ $quantity }}</div>
                            </div>
                        </div>

                        @endforeach
                    @else
                        <p>No items in cart</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 35vw;">
            <div class="modal-content p-3">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-secondary me-2" onclick="printInvoice()">Print</button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="invoiceContent">
                    <h5>
                        <img src="{{ asset('image/tayment_logo.png') }}" alt="Logo" style="width: 70px; height: 70px;">
                        TayMeng
                    </h5>
                    <div class="receipt">
                        <div class="text-center mb-2">
                            <h4>វិក័យប័ត្រ</h4>
                            <div class="receipt-header">
                                <small>#78E0, ផ្លូវ13 សង្កាត់ផ្សារកណ្តាល I ខណ្ឌដូនពេញ រាជធានីភ្នំពេញ</small><br>
                                <small>096841 2222 / 076 333 3288 / 031 333 3288</small>
                            </div>
                        </div>
                        <hr class="my-2">

                        <div class="d-flex justify-content-between mb-2">
                            <p class="mb-0"><strong>Date:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
                            <p class="mb-0"><strong>Order:</strong> {{ $order->order_num }}</p>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <p class="mb-0"><strong>Customer:</strong> {{ $order->guest_name ?? Auth::user()->name }}</p>
                            <p class="mb-0"><strong>Address:</strong> {{ $order->guest_address }}</p>
                        </div>
                        <table class="table table-sm table-bordered" style="text-align: center; font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th style="text-align: center; font-size: 0.9rem;">Product</th>
                                    <th style="text-align: center; font-size: 0.9rem;">Quantity</th>
                                    <th style="text-align: center; font-size: 0.9rem;">Price</th>
                                    <th style="text-align: center; font-size: 0.9rem;">Warranty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $item)
                                    <tr>
                                        <td style="text-align: center; font-size: 0.9rem;">{{ $item->product_name }}</td>
                                        <td style="text-align: center; font-size: 0.9rem;">{{ $item->quantity }}</td>
                                        <td style="text-align: center; font-size: 0.9rem;">
                                            ${{ number_format($item->price, 2) }}</td>
                                        <td style="text-align: center; font-size: 0.9rem;">{{ $item->warranty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between">
                            <strong>Total USD:</strong>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <small>Total (KHR):</small>
                            <span>{{ number_format($order->total_amount * $order->rate) }} ៛</span>
                        </div>

                        <div class="mt-2">
                            <div class="d-flex justify-content-between">
                                <small>Receive (Cash):</small>
                                <span>${{ $order->total_amount }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                អាត្រាប្តូរប្រាក់
                                <span>{{ $order->rate }} ៛</span>
                            </div>
                        </div>

                        <hr class="my-2">
                        <div class="d-flex justify-content-end mt-4">
                            <div style="text-align: center; min-width: 180px;">
                                <p style="font-size: 12px; margin-bottom: 40px;">Prepared by name</p>
                                <hr style="border: none; height: 1px; background-color: #000; margin-bottom: 5px;">
                                <p style="font-size: 12px;">Signature & Date</p>
                            </div>
                        </div>
                        <p class="text-center">
                            {{-- <small>VAT 10%, Exchange Rate: $1 = ៛4,100</small><br> --}}
                            <strong>សូមរក្សាទុកវិក្ក័យប័ត្រនេះដើម្បីបញ្ជាក់ការធានា</strong><br>
                            <small>Thank you, please come back again.</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printInvoice() {
            var printContents = document.getElementById('invoiceContent').innerHTML;
            var originalContents = document.body.innerHTML;
            var win = window.open('', '', 'height=700,width=900');
            win.document.write('<html><head><title>Invoice</title>');
            win.document.write(
                '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">'
            );
            win.document.write('</head><body>');
            win.document.write(printContents);
            win.document.write('</body></html>');
            win.document.close();
            win.focus();
            win.print();
            win.close();
        }
    </script>
@endsection
