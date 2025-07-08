<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Order</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
</head>
<body class="bg-light p-4">

    <h2 class="mb-4">🛒 New Order Placed</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-4">
                <h5 class="text-primary">{{ $order->delivery_type }}</h5>
                <p><strong>Order Number:</strong> {{ $order->order_num }}</p>
                <p><strong>Phone:</strong> {{ $order->phone_guest }}</p>
                <p><strong>Address:</strong> {{ $order->guest_address }}</p>
                <p><strong>Total Amount:</strong> 
                    <span class="fw-bold text-success">
                        ${{ number_format($order->total_amount, 2) }}
                    </span>
                </p>
            </div>

            <div class="bg-light p-3 mb-4 rounded shadow-sm">
                @if(isset($orderItems) && count($orderItems) > 0)
                    @foreach($orderItems as $item)
                        @php
                            $imgSrc = $item->imgSrc ?? '';
                            $title = $item->product_name ?? 'Unknown Product';
                            $color = $item->color_code ?? '#ccc';
                            $size = $item->size ?? 'N/A';
                            $quantity = $item->quantity ?? 0;
                            $price = $item->price ?? '0.00';
                        @endphp
                        <div class="d-flex align-items-center mb-3 p-2 rounded border" style="border-color:#b35dae;">
                            <img src="{{ $imgSrc }}" alt="{{ $title }}" width="120" height="120"
                                 class="me-3 rounded" style="object-fit: cover;">
                            <div>
                                <div><strong>Product Name:</strong> {{ $title }}</div>
                                <div><strong>Color:</strong>
                                    <span style="display:inline-block; width:20px; height:20px; background-color: {{ $color }}; border: 1px solid #ccc;"></span>
                                </div>
                                <div><strong>Size:</strong> {{ $size }}</div>
                                <div><strong>Quantity:</strong> {{ $quantity }}</div>
                                <div class="text-danger"><strong>Price:</strong> ${{ number_format($price, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No items in cart</p>
                @endif
            </div>

            <div class="d-flex justify-content-between gap-3">
                <form action="{{ route('order_dashboard.index') }}" method="GET" class="flex-grow-1">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">
                        ✅ Accept
                    </button>
                </form>

                <form action="{{ route('order_dashboard.index') }}" method="GET" class="flex-grow-1">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        ❌ Decline
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    ></script>
</body>
</html>
