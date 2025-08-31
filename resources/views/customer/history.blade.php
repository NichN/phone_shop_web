@extends('Layout.headerfooter')

@section('title', 'History')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

    {{-- Guest Order Lookup --}}
    @if(!auth()->check())
        <div class="container mt-4 mb-4">
            <h4 class="text-center mb-3">Guest Order History Lookup</h4>
            <form method="GET" action="{{ route('checkout.history') }}" class="row g-2 justify-content-center">
                <div class="col-md-4">
                    <input type="email" name="guest_eamil" class="form-control" placeholder="Your Email" value="{{ request('guest_eamil') }}" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="order_num" class="form-control" placeholder="Order Number" value="{{ request('order_num') }}" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>

            @if(request()->has('guest_eamil') && $orders->isEmpty())
                <p class="text-center mt-3 text-danger">No orders found with provided email and token.</p>
            @endif
        </div>
    @endif

    {{-- Order List --}}
    <div class="container mt-4">
        <h4 class="text-center mb-4">My Order History <i class="fas fa-history text-success"></i></h4>

        @if($orders->isEmpty())
            <p class="text-center text-secondary">No orders to show.</p>
        @endif

        @foreach ($orders as $order)
            <div class="card mb-4">
                <div class="order-card">

                    {{-- Order Header --}}
                    <div class="order-header d-flex justify-content-between align-items-center p-3" style="background-color: #eee7e7;">
                        <div>
                            <strong>Order #{{ $order->order_num }}</strong>
                            <span class="text-muted"> &nbsp; {{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Manage Order
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-primary" href="{{ route('checkout.history_details', $order->id) }}">
                                    <i class="fas fa-eye me-2"></i>Detail</a></li>
                                
                                @if(strtolower($order->status) === 'pending')
                                    <li>
                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#cancelModal-{{ $order->id }}">
                                            <i class="fas fa-times-circle me-2"></i>Cancel Order
                                        </button>
                                    </li>
                                @endif

                                @if (in_array(strtolower($order->status), ['pending', 'processing']))
                                    <li><a class="dropdown-item text-success" href="{{ route('checkout.payment', $order->id) }}">
                                        <i class="fas fa-credit-card me-2"></i>Payment</a></li>
                                @endif

                                {{-- @if (in_array(strtolower($order->status), ['accepted', 'pending']))
                                    <li>
                                        <button type="button" class="dropdown-item text-info verify-order-btn" data-order-id="{{ $order->id }}">
                                            <i class="fas fa-check-circle me-2"></i>Verify Order
                                        </button>
                                    </li>
                                @endif --}}
                                 {{-- 'processing' --}}
                            </ul>
                        </div>
                    </div>

                    {{-- Order Body --}}
                    <div class="order-body p-3 d-flex justify-content-between flex-wrap">

                        {{-- Product Info --}}
                        <div class="order-product d-flex align-items-center gap-3">
                            <img src="https://cdn-icons-png.flaticon.com/512/263/263142.png" alt="Shopping Icon" style="width: 75px; height: 75px;">
                            <div>
                                <p class="mb-1"><strong>Product</strong></p>
                                <p class="mb-1">Total: ${{ number_format($order->total_amount, 2) }}</p>
                                <p class="text-muted small">View product details in your order detail.</p>
                            </div>
                        </div>

                        {{-- Shipping Info --}}
                        <div>
                            <p><strong>Shipping Info</strong></p>
                            <p class="mb-1">{{ $order->guest_name ?? 'Customer Name' }}</p>
                            <p class="mb-1">{{ $order->guest_address ?? '-' }}</p>
                            <p class="mb-1">{{ $order->guest_phone ?? '' }}</p>
                        </div>

                        {{-- Status --}}
                        <div class="package-status">
                            <p><strong>Order Status</strong></p>
                            <p class="
                                {{ strtolower($order->status) === 'cancelled' ? 'text-danger bg-light' : '' }}
                                {{ strtolower($order->status) === 'pending' ? 'text-secondary bg-light' : '' }}
                                {{ strtolower($order->status) === 'accepted' ? 'text-warning bg-light' : '' }}
                                {{ strtolower($order->status) === 'completed' ? 'text-success bg-light' : '' }}
                                p-2 rounded
                            ">
                                {{ ucfirst($order->status) == 'Returned' ? 'Cancelled' : ucfirst($order->status) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Cancel Modal --}}
                <div class="modal fade" id="cancelModal-{{ $order->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('checkout.process_return', $order->id) }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-primary">Cancel Order #{{ $order->order_num }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Why do you want to cancel?</label>
                                    <textarea name="return_reason" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Confirm</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Verify Order Script --}}
    <script>
        $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();

            $('.verify-order-btn').on('click', function (e) {
                e.preventDefault();
                let orderId = $(this).data('order-id');

                Swal.fire({
                    title: 'Enter Verification Code',
                    input: 'text',
                    inputLabel: 'A code has been sent to your email. Please enter it below:',
                    inputAttributes: {
                        maxlength: 6,
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Verify',
                    showLoaderOnConfirm: true,
                    preConfirm: (code) => {
                        if (!code) {
                            Swal.showValidationMessage('Please enter the code.');
                            return;
                        }
                        return $.ajax({
                            url: '/checkout/verify-code',
                            method: 'POST',
                            data: {
                                order_id: orderId,
                                code: code,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }
                        }).then(response => {
                            if (!response.success) {
                                throw new Error(response.message);
                            }
                            return response;
                        }).catch(error => {
                            Swal.showValidationMessage(`Verification failed: ${error}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value?.order_id) {
                        Swal.fire('Success!', 'Verification code confirmed. Redirecting...', 'success')
                            .then(() => {
                                window.location.href = '/checkout/payment/' + result.value.order_id;
                            });
                    } else if (result.isConfirmed) {
                        Swal.fire('Error', 'Order ID not found in response.', 'error');
                    }
                });
            });
        });
    </script>
@endsection
