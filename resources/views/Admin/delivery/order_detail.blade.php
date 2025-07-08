

    @include('Admin.component.sidebar')

    <div class="w3-main">
    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-between flex-wrap gap-2">
            <div>
                <a href="/deliveries" class="btn">← Back</a>
            </div>
            <div>
               <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#invoiceModal">
                Invoice
            </button> 
            </div>
        </div>
        <h4 class="text-center mb-4">
                    Order Details <i class="fas fa-history text-primary"></i>
                </h4>
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <p class="mb-0">
                <strong>Ordered On:</strong> {{ $order->created_at->format('d-m-Y') }}<br>
                <strong>Order:</strong> #{{ $order->order_num }}<br>
            </p>
        </div>

        <div class="card mb-3 p-3" style="max-width: 1000px;">
            <div class="d-flex justify-content-between flex-wrap">
                <div class="col-md-4 col-sm-6 mb-3">
                <h6><strong>Customer Information</strong></h6>
                <p class="mb-1"><strong>Name:</strong> {{ $order->guest_name ?? '-' }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $order->guest_phone ?? '-' }}</p>
                <p class="mb-1">
                    <strong>Address:</strong> {{ $order->guest_address ?? $order->address ?? '-' }}
                </p>
            </div>


                <div class="label" style="min-width: 200px;">
                    <p><strong>Payment Method</strong></p>
                    <p>{{ $payment->payment_type ?? 'N/A' }}</p>
                </div>

                <div class="label" style="min-width: 200px;">
                    <p><strong>Order Summary</strong></p>
                    <p>Item Quantity: {{ $orderItems->sum('quantity') }}</p>
                    <p>Subtotal: <span style="color:blue;">${{ number_format($order->subtotal, 2) }}</span></p>
                    <p>Delivery Fee: ${{ number_format($delivery->fee ?? 0, 2) }}</p>
                    <p>Grand Total: ${{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <div class="bg-light p-3 mb-3 rounded shadow-sm">
                @if(isset($orderItems) && $orderItems->isNotEmpty())
                    @foreach ($orderItems as $item)
                        @php
                            $images = json_decode($item->imgSrc, true);
                            $imgSrc = ($images && count($images) > 0) ? $images[0] : 'default-image.jpg';
                            $title = $item->product_name ?? 'Unknown Product';
                            $color = $item->color_code ?? '#ccc';
                            $size = $item->size ?? 'N/A';
                            $quantity = $item->quantity ?? 0;
                            $price = $item->price ?? '0.00';
                        @endphp
                        <div class="d-flex align-items-center mb-3 p-2 rounded" style="border:1px solid #b35dae;">
                            <img src="{{ asset('storage/' . $imgSrc) }}" alt="{{ $title }}" width="120" height="120"
                                class="me-3 rounded" style="object-fit: cover;">
                            <div>
                                <div><strong>Product Name:</strong> {{ $title }}</div>
                                <div><strong>Color:</strong> 
                                    <span style="display:inline-block; width:20px; height:20px; background-color: {{ $color }}; border: 1px solid #ccc;"></span>
                                </div>
                                <div><strong>Size:</strong> {{ $size }}</div>
                                <div><strong>Quantity:</strong> {{ $quantity }}</div>
                                <div class="text-danger"><strong>Price:</strong> ${{ $price }}</div>
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
          <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="receipt">
                <div class="receipt-header">
                    <h5>
                        <img src="{{ asset('image/tayment_logo.png') }}" alt="Logo" style="width: 70px; height: 70px;">
                        TayMeng
                    </h5>
                    <small>#78E0, ផ្លូវ13 សង្កាត់ផ្សារកណ្តាល I ខណ្ឌដូនពេញ រាជធានីភ្នំពេញ</small><br>
                    <small>096841 2222 / 076 333 3288 / 031 333 3288</small>
                </div>

                <hr class="my-2">
                <div class="text-center mb-2">
                    <strong>វិក័យបត្រ / RECEIPT</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <p class="mb-0"><strong>Date:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
                    <p class="mb-0"><strong>Order:</strong> {{ $order->order_num }}</p>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <p class="mb-0"><strong>Customer:</strong> {{ $order->guest_name ?? Auth::user()->name }}</p>
                    <p class="mb-0"><strong>Address:</strong> {{ $order->address }}</p>
                </div>
                <table class="table table-sm table-bordered" style="text-align: center; font-size: 0.9rem;">
                    <thead>
                        <tr>
                            <th style="text-align: center; font-size: 0.9rem;">Item Name</th>
                            <th style="text-align: center; font-size: 0.9rem;">Qty</th>
                            <th style="text-align: center; font-size: 0.9rem;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderItems as $item)
                            <tr>
                                <td style="text-align: center; font-size: 0.9rem;">{{ $item->product_name }}</td>
                                <td style="text-align: center; font-size: 0.9rem;">{{ $item->quantity }}</td>
                                <td style="text-align: center; font-size: 0.9rem;">${{ number_format($item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr class="my-1 border-top-dashed">
                <div class="d-flex justify-content-between">
                    <strong>Total USD:</strong>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>

                <div class="d-flex justify-content-between">
                    <small>Total (KHR):</small>
                    <span>{{ number_format($order->total_amount * 4100, 0) }} ៛</span>
                </div>

                <div class="mt-2">
                    <div class="d-flex justify-content-between">
                        <small>Receive (Cash):</small>
                        <span>${{ $order->total_amount }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>អាត្រាប្តូរប្រាក់</strong>
                        <span>$0.00 | ៛0</span>
                    </div>
                </div>

                <hr class="my-2">

                <p class="text-center">
                    {{-- <small>VAT 10%, Exchange Rate: $1 = ៛4,100</small><br>
                    <strong>សូមអរគុណ! សូមអញ្ជើញមកម្តងទៀត</strong><br> --}}
                    <small>Thank you, please come back again.</small>
                </p>
            </div>
          </div>
        </div>
      </div>
    </div>

