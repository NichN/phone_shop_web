@extends('Layout.headerfooter')

@section('title', 'Invoice')

@section('content')
<div class="container my-5">
    <a href="{{ route('history') }}" onclick="history.back()" class="btn btn-secondary mb-4">← Back</a>

    <div class="border p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="fw-bold">TayMeng</h5>
                <p class="mb-1">{{ $order['date'] }}</p>
                <p class="mb-1">#{{ $order['id'] }}</p>
            </div>
            <div class="text-end">
                <p class="mb-1"><strong>Customer name:</strong> {{ $order['customer_name'] }}</p>
                <p class="mb-1"><strong>Address:</strong> {{ $order['address'] }}</p>
            </div>
        </div>

        <h4 class="text-center fw-bold my-4">INVOICE</h4>

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order['items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td><strong>${{ number_format($item['price'], 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-4 me-2">
            <hr>
            <p><strong>Subtotal:</strong> ${{ number_format($order['subtotal'], 2) }}</p>
            <p><strong>Tax:</strong> ${{ number_format($order['tax'], 2) }}</p>
            <p class="text-danger fw-bold"><strong>Total:</strong> ${{ number_format($order['total'], 2) }}</p>
            <hr>
        </div>

        <div class="mt-4">
            <p><strong>Thanks for your order!</strong></p>
            <p class="text-muted small">Please pay within 15 days of receiving the product.</p>
        </div>
    </div>
</div>
@endsection
