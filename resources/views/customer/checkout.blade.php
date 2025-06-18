@extends('Layout.headerfooter')

@section('title', 'CheckOut')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-center flex-grow-1">Checkout</h4>
            <div style="width: 90px;"></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="bg-light p-3 mb-3 rounded shadow-sm">
                    @if(isset($orderItems) && is_array($orderItems))
                        @foreach ($orderItems as $item)
                        <div class="d-flex align-items-center mb-3 p-2 rounded" style="border:1px solid#b35dae;">
                            <img src="{{ $item['imgSrc'] }}" alt="{{ $item['title'] }}" width="120"
                                height="120" class="me-3 rounded" style="object-fit: cover;">
                            <div>
                                <div><strong>Product Name:</strong> {{ $item['title'] }}</div>
                                <div><strong>Color:</strong> 
                                    <span style="display:inline-block; width:20px; height:20px; background-color: {{ $item['color'] }}; border: 1px solid #ccc;"></span>
                                </div>
                                <div><strong>Size:</strong> {{ $item['size'] }}</div>
                                <div><strong>Quantity:</strong> {{ $item['quantity'] }}</div>
                                <div class="text-danger"><strong>Price:</strong> ${{ $item['price'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p>No items in cart</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <form id="checkoutForm" method="POST" action="{{ route('checkout.store') }}">
                    <input type="hidden" name="cart_data" id="cartItemsInput">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Continue to shopping as</label>
                        <div>
                            <input type="radio" id="guest" name="user_type" value="guest" checked>
                            <label for="guest">Guest</label>

                            <input type="radio" id="register" name="user_type" value="register" style="margin-left: 20px;">
                            <label for="register">Register Account</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="guest_name" class="form-label">Name</label>
                        <input type="text" name="guest_name" class="form-control" id="guest_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_guest" class="form-label">Phone Number</label>
                        <input type="text" name="phone_guest" class="form-control" id="phone_guest" required>
                    </div>
                    <div class="mb-3">
                        <label for="guest_address" class="form-label">Shipping Address</label>
                        <input type="text" name="guest_address" class="form-control" id="guest_address" required>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>${{ $subtotal }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-primary">Delivery fee</span>
                            <span class="text-danger">${{ $deliveryFee }}</span>
                        </div>
                        <h3 style="border:1px solid gray;"></h3>
                        <div class="d-flex justify-content-between fw-bold mt-3">
                            <span>Total Amount</span>
                            <span>${{ $totalAmount }}</span>
                        </div>
                        <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                        <input type="hidden" name="delivery_fee" value="{{ $deliveryFee }}">
                        <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                    </div>
                    <button type="submit" class="btn w-100" style="background-color: #dbb3d9;">Continue to shopping</button>
                </form>
            </div>
        </div>
    </div>
<script src="{{ asset('js/cart.js') }}"></script>
@include('customer.script_order')
@endsection
