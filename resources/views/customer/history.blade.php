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
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="Returned" {{ request('status') == 'Returned' ? 'selected' : '' }}>Returned</option>
                        <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing
                        </option>
                    </select>
                </div>

                <div class="col-md-3 mb-2">
                    <input type="text" class="form-control" name="search" placeholder="Search Order"
                        value="{{ request('search') }}">
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
            <div class="order-card">

                {{-- Order Header --}}
                <div class="order-header">
                    <div>
                        <strong>Order #{{ $order->order_num }}</strong>
                        <span class="text-muted"> &nbsp; {{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="me-3"><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</span>
                        <div class="dropdown d-inline">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-primary"
                                        href="{{ route('checkout.history_details', $order->id) }}"><i
                                            class="fas fa-eye me-2"></i>Detail</a></li>
                                <li><button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                                        data-bs-target="#cancelModal-{{ $order->id }}"><i
                                            class="fas fa-times-circle me-2"></i>Cancel Order</button></li>
                                <li><a class="dropdown-item text-success"
                                        href="{{ route('checkout.payment', $order->id) }}"><i
                                            class="fas fa-credit-card me-2"></i>Payment</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Order Body --}}
                <div class="order-body">

                    {{-- Product Info --}}
                    <div class="order-product">
                        <img src="https://cdn-icons-png.flaticon.com/512/263/263142.png" alt="Shopping Icon"
                            style="width:60px;height:60px;">
                        <div>
                            {{-- You can replace these with real order info --}}
                            <p class="mb-1"><strong>{{ $order->total_items ?? '1 item' }}</strong></p>
                            <p class="mb-1">Category: {{ $order->category ?? 'General' }}</p>
                            <p class="text-muted small">Payment: {{ $order->payment_method ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- Shipping Info --}}
                    <div>
                        <p><strong>Shipping Info</strong></p>
                        <p class="mb-1">{{ $order->guest_name ?? 'Customer Name' }}</p>
                        <p class="mb-1">{{ $order->guest_address ?? '-' }}</p>
                        <p class="mb-1">{{ $order->guest_phone ?? '' }}</p>
                    </div>

                    {{-- Package Status --}}
                    <div class="package-status">
                        <p><strong>Order Status</strong></p>
                        <p><span
                                class=" @if ($order->status === 'Delivered') text-success 
                            @elseif($order->status === 'Complete') text-primary
                            @elseif($order->status === 'Cancelled') text-danger 
                            @else text-secondary @endif">
                                {{ $order->status }}
                            </span>
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
        @endforeach
    </div>
@endsection
