@extends('Layout.headerfooter')

@section('title', 'History')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
@if(!auth()->check())
<div class="container mt-4 mb-4">
    <h4 class="text-center mb-3">Guest Order History Lookup</h4>
    <form method="GET" action="{{ route('checkout.history') }}" class="row g-2 justify-content-center">
        <div class="col-md-4">
            <input type="email" name="guest_eamil" class="form-control" placeholder="Your Email" value="{{ request('guest_eamil') }}" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="order_num" class="form-control" placeholder="Order Number" value="{{ request('order_num') }}" required>
        </div><br>
    </form>

    {{-- Debug info: remove after confirming it works --}}
    <div class="mt-3 text-center text-muted">
        <pre>Guest Email: {{ request('guest_eamil') }}</pre>
        <pre>Order Number: {{ request('order_num') }}</pre>
        <pre>Orders Found: {{ $orders->count() }}</pre>
    </div>

    @if(request()->has('guest_eamil') && $orders->isEmpty())
        <p class="text-center mt-3 text-danger">No orders found with provided email and token.</p>
    @endif
</div>
@endif
<div class="container mt-4">
    <h4 class="text-center mb-4">
        My Order History
        <i class="fas fa-history text-success"></i>
    </h4>

    @if($orders->isEmpty())
        <p class="text-center text-secondary">No orders to show.</p>
    @endif

    @foreach ($orders as $order)
    <div class="card mb-4">
        <div class="display-flex">
            <div class="card-header d-flex justify-content-between align-items-center text-black fs-6" style="background-color: #eee7e7;">
    <span>Order {{ $order->order_num }}</span>
    <div class="d-flex gap-3">
    {{-- Detail --}}
    <a data-bs-toggle="tooltip" href="{{ route('checkout.history_details', $order->id) }}" class="text-primary" title="View Detail">
        <i class="fas fa-eye fa-lg"></i>
    </a>

    {{-- Verify Order (if pending) --}}
    @if(in_array(strtolower($order->status), ['accepted', 'pending']))
        <a href="#" data-bs-toggle="tooltip" class="text-success verify-order-btn" title="Verify Order" data-order-id="{{ $order->id }}">
            <i class="fas fa-check-circle fa-lg"></i>
        </a>
    @endif

    {{-- Add Payment (if accepted) --}}
    @if(in_array(strtolower($order->status), ['accepted', 'processing']))
        <a data-bs-toggle="tooltip" href="{{ route('checkout.payment', $order->id) }}" class="text-primary" title="Add Payment">
            <i class="fa-solid fa-money-bill text-success"></i>
        </a>
    @endif

    {{-- Cancel Order (if not completed) --}}
    @if(strtolower($order->status) === 'pending')
        <button type="button" class="btn p-0 text-danger" title="Cancel Order" data-bs-toggle="modal" data-bs-target="#cancelModal-{{ $order->id }}">
            <i class="fas fa-times-circle fa-lg"></i>
        </button>
@endif

</div>

</div>
</div>
    <div class="card-body d-flex gap-5">
    <div class="label" style="min-width: 120px;">
        <p class="mb-2"><strong>Date:</strong></p>
        <p class="mb-2"><strong>Order:</strong></p>
        <p class="mb-2"><strong>Address:</strong></p>
        <p class="mb-2"><strong>Amount:</strong></p>
        <p class="mb-2"><strong>Order Status:</strong></p>
    </div>

    <div class="result" style="flex: 1;">
        <p class="mb-2">{{ $order->created_at->format('d-m-Y') }}</p>
        <p class="mb-2">{{ $order->order_num }}</p>
        <p class="mb-2">{{ $order->guest_address ?? '-' }}</p>
        <p class="mb-2">${{ number_format($order->total_amount, 2) }}</p>
        @php
            switch ($order->status) {
                case 'pending':
                    $textClass = 'text-warning';
                    break;
                case 'completed':
                    $textClass = 'text-success';
                    break;
                case 'cancelled':
                    $textClass = 'text-danger';
                    break;
                case 'processing':
                    $textClass = 'text-primary';
                    break;
                default:
                    $textClass = 'text-info';
            }
        @endphp
    <p class="mb-2 {{ $textClass }}">
        {{ ucfirst($order->status) }}
    </p>

    </div>
</div>

    </div>
    <div class="modal fade" id="cancelModal-{{ $order->id }}" tabindex="-1" aria-labelledby="cancelModalLabel-{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('checkout.process_return', $order->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="cancelModalLabel-{{ $order->id }}">Order #{{ $order->order_num }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cancel_reason_{{ $order->id }}" class="form-label">Why You Want Cancel?</label>
                            <textarea name="return_reason" id="return_reason_{{ $order->id }}" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>
<script>
    $(document).ready(function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        $('.verify-order-btn').on('click', function (e) {
    e.preventDefault();

    let orderId = $(this).data('order-id');

    Swal.fire({
        title: 'Enter Verification Code',
        input: 'text',
        inputLabel: 'A code has been sent to your email. Please enter it below:',
        inputAttributes: { maxlength: 6, autocapitalize: 'off', autocorrect: 'off' },
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
        if (result.isConfirmed && result.value && result.value.order_id) {
            Swal.fire(
                'Success!',
                'Verification code confirmed. Redirecting...',
                'success'
            ).then(() => {
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
