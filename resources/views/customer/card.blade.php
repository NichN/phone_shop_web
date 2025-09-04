@extends('Layout.headerfooter')
<link href="{{ asset('css/payment.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Load jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- THEN load other scripts that use jQuery -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@section('title', 'Payment')
@section('content')
<div class="container py-4">
    <form method="POST" action="{{ route('checkout.payment_store') }}">
        @csrf
        <div class="row justify-content-between">
            {{-- Left Side - Order Summary --}}
            <div class="col-md-8">
                <h5 class="fw-bold mb-4 text-center">
                    My Shopping Bag ({{ $orderItems->sum('quantity') }})
                </h5>

                <div class="bg-light p-3 mb-3 rounded shadow-sm">
                    @if(isset($orderItems) && $orderItems->isNotEmpty())
                        @foreach ($orderItems as $item)
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
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><b>Subtotal</b></span>
                        <span><strong>${{ $subtotal ?? '0.00' }}</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-success"><b>Delivery fee</b></span>
                        <span class="text-success"><strong>${{ $deliveryFee ?? '0.00' }}</strong></span>
                    </div>
                    <hr style="border-color: 1px solid black;">
                    <div class="d-flex justify-content-between fw-bold mt-3">
                        <span class="text-danger">Total Amount</span>
                        <span style="color: red">${{ $totalAmount ?? '0.00' }}</span>
                    </div>

                    <input type="hidden" name="subtotal" value="{{ $subtotal ?? '0.00' }}">
                    <input type="hidden" name="delivery_fee" value="{{ $deliveryFee ?? '0.00' }}">
                    <input type="hidden" name="total_amount" value="{{ $totalAmount ?? '0.00' }}">
                </div>
            </div>

           {{-- <input type="hidden" name="rate_id" id="rate_id"> --}}
            <div class="col-md-4">
                <div class="payment-methods-container">
                <h5 class="fw-bold mb-3">Payment Method</h5>
                <div class="payment-options">
                    @if($deliveryType === 'pick up')
                        {{-- Only KH QR Payment --}}
                        <div class="payment-option">
                            <input class="form-check-input" type="radio" name="payment_type" id="kh_qr" value="online_payment" checked>
                            <label class="payment-label" for="kh_qr">
                                <div class="payment-content d-flex align-items-center">
                                    <img src="{{ asset('image/barkog.png') }}" alt="KH QR Payment" class="payment-icon rounded me-2">
                                    <div>
                                        <span class="payment-title">Upload Screenshot Payment</span><br>
                                        <small class="payment-description">Scan QR code to pay instantly</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input class="form-check-input" type="radio" name="payment_type" id="take_sh" value="Cash at Pickup" checked>
                            <label class="payment-label" for="take_sh">
                                <div class="payment-content d-flex align-items-center">
                                    <img src="{{ asset('image/shop.png') }}" alt="shop pickup" class="payment-icon rounded me-2">
                                    <div>
                                        <span class="payment-title">Cash at Pickup</span><br>
                                        <small class="payment-description">Pay When You Arrived Shop</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @else
                        {{-- Cash on Delivery --}}
                        <div class="payment-option mb-2">
                            <input class="form-check-input" type="radio" name="payment_type" id="cash_on_delivery" value="cash on delivery" checked>
                            <label class="payment-label" for="cash_on_delivery">
                                <div class="payment-content d-flex align-items-center">
                                    <img src="{{ asset('image/delivery.png') }}" alt="Cash on Delivery" class="payment-icon me-2">
                                    <div>
                                        <span class="payment-title">Cash on Delivery</span><br>
                                        <small class="payment-description">Pay when you receive your items</small>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- KH QR Payment --}}
                        <div class="payment-option">
                            <input class="form-check-input" type="radio" name="payment_type" id="kh_qr" value="online_payment">
                            <label class="payment-label" for="kh_qr">
                                <div class="payment-content d-flex align-items-center">
                                    <img src="{{ asset('image/barkog.png') }}" alt="Upload Screenshot Payment" class="payment-icon rounded me-2">
                                    <div>
                                        <span class="payment-title">Upload Screenshot Payment</span><br>
                                        <small class="payment-description">Scan QR code to pay instantly</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endif
                </div>
            </div>
                <div class="mt-3">
                    <label for="note"><strong>Note:</strong></label>
                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Add a note (optional)"></textarea>
                </div>
                <input type="hidden" name="order_id" value="{{ $orderItems[0]->order_id ?? '' }}">

                <div class="center-container mt-4">
                    <button type="submit" id="confirmOrderBtn">Let's Pay</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@include('customer.script_payment')
