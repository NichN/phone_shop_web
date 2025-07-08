@extends('Layout.headerfooter')

@section('title', 'CheckOut')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <h5 class="mb-0 text-center flex-grow-1">
                <i class="fas fa-shopping-bag text-success me-2"></i>
                Let’s wrap up your shopping bong, <b>{{ Auth::check() ? Auth::user()->name : 'Guest' }}!</b>
            </h5>
        </div>
        <div style="width: 90px;"></div>
    </div>

    <div class="row">
        <!-- Left side - Items -->
        <div class="col-md-6">
            <div class="bg-light p-3 mb-3 rounded shadow-sm">
                @if(isset($orderItems) && is_array($orderItems) && count($orderItems) > 0)
                    @foreach ($orderItems as $item)
                        @php
                            $imgSrc = $item['imgSrc'] ?? 'default-image.jpg';
                            $title = $item['title'] ?? 'Unknown Product';
                            $color = $item['color'] ?? '#ccc';
                            $size = $item['size'] ?? 'N/A';
                            $quantity = $item['quantity'] ?? 0;
                            $price = $item['price'] ?? '0.00';
                        @endphp
                        <div class="d-flex align-items-center mb-3 p-2 rounded" style="border:1px solid #b35dae;">
                            <img src="{{ $imgSrc }}" alt="{{ $title }}" width="120" height="120"
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
       <input type="hidden" name="rate_id" id="rate_id">
        <div class="col-md-6">
            <div id="order-alert"></div>
            <form id="checkoutForm" method="POST" action="{{ route('checkout.store') }}">
                @csrf
                <input type="hidden" name="cart_data" id="cartItemsInput">

                <!-- User type -->
                <div class="mb-3">
                    <label class="form-label"><strong>Continue to shopping as</strong></label>
                    <div>
                        <input type="radio" id="guest" name="user_type" value="guest" checked>
                        <label for="guest">Guest</label>

                        <input type="radio" id="register" name="user_type" value="register" style="margin-left: 20px;">
                        <label for="register">Register Account</label>
                    </div>
                </div>

                <!-- Delivery method -->
                <div class="mb-3">
                    <label class="form-label">Delivery Method</label>
                    <div>
                        <input type="radio" id="pick_up" name="delivery_method" value="pick up" checked>
                        <label for="pick_up">Pick Up</label>

                        <input type="radio" id="delivery" name="delivery_method" value="delivery" style="margin-left: 20px;">
                        <label for="delivery">Delivery</label>
                    </div>
                </div>

                <!-- User data -->
                <input type="hidden" id="userData"
                    data-name="{{ $user->name ?? '' }}"
                    data-phone="{{ $user->phone_number ?? '' }}"
                    data-email="{{ $user->email ?? '' }}"
                    data-address="{{ ($user->address_line1 ?? '') . ' ' . ($user->address_line2 ?? '') }}">

                <div class="mb-3">
                    <label for="guest_name" class="form-label">Name</label>
                    <input type="text" name="guest_name" class="form-control" id="guest_name" required>
                </div>

                <div class="mb-3">
                    <label for="phone_guest" class="form-label">Phone Number</label>
                    <input type="text" name="phone_guest" class="form-control" id="phone_guest" required>
                </div>

                <div class="mb-3">
                    <label for="email_guest" class="form-label">Email</label>
                    <input type="email" name="email_guest" class="form-control" id="email_guest" required>
                </div>

                <div class="mb-3">
                    <label for="guest_address" class="form-label">Shipping Address</label>
                    <input type="text" name="guest_address" class="form-control" id="guest_address" required>
                </div>

                <!-- Totals -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span><b>Subtotal</b></span>
                        <span><strong>${{ $subtotal ?? '0.00' }}</strong></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-success"><b>Delivery fee</b></span>
                        <span id="deliveryFeeDisplay" class="text-success"><strong>${{ $deliveryFee ?? '0.00' }}</strong></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold mt-3">
                        <span class="text-danger">Total Amount</span>
                        <span id="totalAmountDisplay" style="color: red">${{ $totalAmount ?? '0.00' }}</span>
                    </div>

                    <input type="hidden" name="subtotal" id="subtotalInput" value="{{ $subtotal ?? '0.00' }}">
                    <input type="hidden" name="delivery_fee" id="deliveryFeeInput" value="{{ $deliveryFee ?? '0.00' }}">
                    <input type="hidden" name="total_amount" id="totalAmountInput" value="{{ $totalAmount ?? '0.00' }}">
                </div>
                {{-- <button type="submit" class="btn text-white" style="background-color: #dbb3d9;">Continue to shopping</button> --}}

                {{-- <a href="#" class="btn" style="background-color: #dbb3d9; padding: 10px 20px; color: white; text-decoration: none; border: none; border-radius: 4px; display: inline-block;">Continue to shopping</a> --}}
                  <div style="text-align: right; margin-top: 20px;">
                <button style="text-decoration: none;" class="arrow-button">
                    Continue to shopping <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/cart.js') }}"></script>
@include('customer.script_order')
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const userData = document.getElementById('userData');
    const guestNameInput = document.getElementById('guest_name');
    const phoneGuestInput = document.getElementById('phone_guest');
    const emailGuestInput = document.getElementById('email_guest');
    const guestAddressInput = document.getElementById('guest_address');
    const guestRadio = document.getElementById('guest');
    const registerRadio = document.getElementById('register');
    const pickUpRadio = document.getElementById('pick_up');
    const deliveryRadio = document.getElementById('delivery');

    const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');
    const totalAmountDisplay = document.getElementById('totalAmountDisplay');
    const deliveryFeeInput = document.getElementById('deliveryFeeInput');
    const totalAmountInput = document.getElementById('totalAmountInput');
    const subtotalInput = document.getElementById('subtotalInput');

    const defaultDeliveryFee = parseFloat(deliveryFeeInput.value);
     const cleanedValue = subtotalInput.value.replace(/,/g, "");
     const subtotal = parseFloat(cleanedValue);

    function fillUserData() {
        if (userData && userData.dataset) {
            guestNameInput.value = userData.dataset.name || '';
            phoneGuestInput.value = userData.dataset.phone || '';
            guestAddressInput.value = userData.dataset.address || '';
            emailGuestInput.value = userData.dataset.email || '';
        }
    }

    function clearUserData() {
        guestNameInput.value = '';
        phoneGuestInput.value = '';
        guestAddressInput.value = '';
        emailGuestInput.value = '';
    }

    function toggleAddressAndFee() {
        let deliveryFee = defaultDeliveryFee;

        if (pickUpRadio.checked) {
            guestAddressInput.closest('.mb-3').style.display = 'none';
            guestAddressInput.required = false;
            deliveryFee = 0;
        } else {
            guestAddressInput.closest('.mb-3').style.display = 'block';
            guestAddressInput.required = true;
            deliveryFee = defaultDeliveryFee;
        }

        const totalAmount = subtotal + deliveryFee;
        console.log(totalAmount);
        deliveryFeeDisplay.innerHTML = '<strong>$' + deliveryFee.toFixed(2) + '</strong>';
        totalAmountDisplay.textContent = '$' + totalAmount;

        deliveryFeeInput.value = deliveryFee.toFixed(2);
        totalAmountInput.value = totalAmount.toFixed(2);
    }

    if (window.isAuthenticated) {
        registerRadio.checked = true;
        guestRadio.checked = false;
        guestRadio.disabled = true;
        guestRadio.parentElement.style.opacity = 0.5;
        fillUserData();
    } else {
        guestRadio.checked = true;
        registerRadio.checked = false;
        clearUserData();
    }

    guestRadio.addEventListener('change', clearUserData);
    registerRadio.addEventListener('change', fillUserData);

    pickUpRadio.addEventListener('change', toggleAddressAndFee);
    deliveryRadio.addEventListener('change', toggleAddressAndFee);

    Swal.fire({
        text: 'How would you like to receive your order?',
        imageUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyGvJdXBAXsEiRXZwKs9aBF0rsiRLfvNKblw&s',
        showDenyButton: true,
        confirmButtonText: '🚚 Delivery',
        denyButtonText: '🛍️ Pick Up',
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            confirmButton: 'swal-delivery-btn',
            denyButton: 'swal-pickup-btn'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            deliveryRadio.checked = true;
        } else {
            pickUpRadio.checked = true;
        }
        toggleAddressAndFee();
        if (window.isAuthenticated) {
            fillUserData();
        } else {
            clearUserData();
        }
    });
});

</script>

