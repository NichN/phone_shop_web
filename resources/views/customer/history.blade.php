@extends('Layout.headerfooter')

@section('title', 'History')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
@endsection

@section('content')
<div class="container mt-4 mb-5">
    <h4 class="text-center mb-4">
        My Order History
        <i class="fas fa-history text-primary"></i>
    </h4>

    <form method="GET" action="{{ route('checkout.history') }}">
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Product</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Returned" {{ request('status') == 'Returned' ? 'selected' : '' }}>Returned</option>
                    <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <input type="text" class="form-control" name="search" placeholder="Search Order" value="{{ request('search') }}">
            </div>

            <div class="col-md-3 mb-2">
                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
            </div>

            <div class="col-md-3 mb-2">
                <a href="{{ route('checkout.history') }}" class="btn btn-dark w-100">Reset</a>
            </div>
        </div>
    </form>

    @foreach ($orders as $order)
    <div class="card mb-3" style="padding:10px; border-radius:15px; max-width: 1000px;">
        <div class="d-flex gap-5">
            <div class="label" style="min-width: 120px;">
                <p style="margin:3px 0;"><strong>Date:</strong></p>
                <p style="margin:3px 0;"><strong>Order:</strong></p>
                <p style="margin:3px 0;"><strong>Address:</strong></p>
                <p style="margin:3px 0;"><strong>Amount:</strong></p>
                <p style="margin:3px 0;"><strong>Order Status</strong></p>
            </div>

            <div class="result" style="flex:1;">
                <p style="margin:3px 0;">{{ $order->created_at->format('d-m-Y') }}</p>
                <p style="margin:3px 0;">{{ $order->order_num }}</p>
                <p style="margin:3px 0;">{{ $order->guest_address ?? '-' }}</p>
                <p style="margin:3px 0;">${{ number_format($order->total_amount, 2) }}</p>
                <p style="margin:3px 0;" class="{{ $order->status === 'cancelled' ? 'text-danger' : 'text-warning' }}">{{ ucfirst($order->status) }}</p>
            </div>

            <div class="dropdown">
                <button class="btn btn-link text-primary p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-lg"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item text-primary" href="{{ route('checkout.history_details', $order->id) }}">
                            <i class="fas fa-eye me-2"></i>Detail
                        </a>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#cancelModal-{{ $order->id }}">
                            <i class="fas fa-times-circle me-2"></i>Cancel Order
                        </button>
                    </li>
                    <li>
                    <a class="dropdown-item text-success" href="{{ route('checkout.payment', $order->id) }}">
                        <i class="fas fa-credit-card me-2"></i>Payment
                    </a>
                </li>

                </ul>
            </div>
        </div>
    </div>

    <!-- Cancel Reason Modal -->
    <div class="modal fade" id="cancelModal-{{ $order->id }}" tabindex="-1" aria-labelledby="cancelModalLabel-{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('checkout.process_return', $order->id) }}">
                @csrf
                {{-- @method('PATCH') --}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="cancelModalLabel-{{ $order->id }}">Order #{{ $order->order_num }}</h5>
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
@endsection
