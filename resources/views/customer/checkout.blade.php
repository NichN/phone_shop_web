@extends('Layout.headerfooter')

@section('title', 'CheckOut')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('homepage') }}" class="btn btn-secondary">← Back</a>
            <h2 class="mb-0 text-center flex-grow-1">Checkout</h2>
            <div style="width: 90px;"></div>
        </div>

        <div class="row">
            <!-- Order Summary -->
            <div class="col-md-6">
                <h5 class="fw-bold">Order Summary</h5>
                <div class="bg-light p-3 mb-3 rounded shadow-sm">
                    @foreach ($orderItems as $item)
                        <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: #ffe6f0;">
                            <img src="{{ asset('image/' . $item['image']) }}" alt="{{ $item['name'] }}" width="120"
                                height="120" class="me-3 rounded" style="object-fit: cover;">
                            <div>
                                <div><strong>Product Name:</strong> {{ $item['name'] }}</div>
                                <div><strong>Category:</strong> {{ $item['category'] }}</div>
                                <div class="text-danger"><strong>Price:</strong> ${{ $item['price'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping and Payment -->
            <div class="col-md-6">
                <h5 class="fw-bold">Shipping and Payment Method</h5>
                <form id="checkoutForm" method="GET">
                    @csrf
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" id="phone"
                            placeholder="Enter Phone Number">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Shipping Address</label>
                        <input type="text" name="address" class="form-control" id="address"
                            placeholder="Enter Your Address">
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>${{ $subtotal }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-danger">
                            <span>Delivery fee</span>
                            <span>${{ $deliveryFee }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total Amount</span>
                            <span>${{ $totalAmount }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="payment" class="form-label fw-bold">Payment Method</label>
                        <select class="form-select" name="payment_method" id="payment" required>
                            <option value="" disabled selected hidden>Select a payment method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Credit Card</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Order Now</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const method = document.getElementById('payment').value;

            if (method === 'cash') {
                window.location.href = "{{ route('invoice') }}";
            } else if (method === 'card') {
                window.location.href = "{{ route('payment.card') }}";
            } else {
                alert("Please select a payment method.");
            }
        });
    </script>

@endsection
