<h5>
    <img src="{{ asset('image/tayment_logo.png') }}" alt="Logo" style="width: 70px; height: 70px;">
    TayMeng
</h5>
<div class="receipt">
    <div class="text-center mb-2">
        <h4>វិក័យប័ត្រ</h4>
        <div class="receipt-header">
            <small>#78E0, ផ្លូវ13 សង្កាត់ផ្សារកណ្តាល I ខណ្ឌដូនពេញ រាជធានីភ្នំពេញ</small><br>
            <small>096841 2222 / 076 333 3288 / 031 333 3288</small>
        </div>
    </div>
    <hr class="my-2">

    <div class="d-flex justify-content-between mb-2">
        <p class="mb-0"><strong>Date:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
        <p class="mb-0"><strong>Order:</strong> {{ $order->order_num }}</p>
    </div>

    <div class="d-flex justify-content-between mb-2">
        <p class="mb-0"><strong>Customer:</strong> {{ $order->guest_name ?? Auth::user()->name }}</p>
        <p class="mb-0"><strong>Address:</strong> {{ $order->guest_address }}</p>
    </div>
    <table class="table table-sm table-bordered" style="text-align: center; font-size: 0.9rem;">
        <thead>
            <tr>
                <th style="text-align: center; font-size: 0.9rem;">Product</th>
                <th style="text-align: center; font-size: 0.9rem;">Quantity</th>
                <th style="text-align: center; font-size: 0.9rem;">Price</th>
                <th style="text-align: center; font-size: 0.9rem;">Warranty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItems as $item)
                <tr>
                    <td style="text-align: center; font-size: 0.9rem;">{{ $item->product_name }}</td>
                    <td style="text-align: center; font-size: 0.9rem;">{{ $item->quantity }}</td>
                    <td style="text-align: center; font-size: 0.9rem;">
                        ${{ number_format($item->price, 2) }}</td>
                    <td style="text-align: center; font-size: 0.9rem;">{{ $item->warranty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <strong>Total USD:</strong>
        <span>${{ number_format($order->total_amount, 2) }}</span>
    </div>

    <div class="d-flex justify-content-between">
        <small>Total (KHR):</small>
        <span>{{ number_format($order->total_amount * $order->rate) }} ៛</span>
    </div>

    <div class="mt-2">
        <div class="d-flex justify-content-between">
            <small>Receive (Cash):</small>
            <span>${{ $order->total_amount }}</span>
        </div>
        <div class="d-flex justify-content-between">
            អាត្រាប្តូរប្រាក់
            <span>{{ $order->rate }} ៛</span>
        </div>
    </div>

    <hr class="my-2">
    <div class="d-flex justify-content-end mt-4">
        <div style="text-align: center; min-width: 180px;">
            <p style="font-size: 12px; margin-bottom: 40px;">Prepared by name</p>
            <hr style="border: none; height: 1px; background-color: #000; margin-bottom: 5px;">
            <p style="font-size: 12px;">Signature & Date</p>
        </div>
    </div>
    <p class="text-center">
        <strong>សូមរក្សាទុកវិក្ក័យប័ត្រនេះដើម្បីបញ្ជាក់ការធានា</strong><br>
        <small>Thank you, please come back again.</small>
    </p>
</div>
 <div class="text-center mt-4">
        <button class="btn btn-secondary" onclick="printInvoice()">Print Invoice</button>
    </div>

<script>
    function printInvoice() {
        const printContents = document.querySelector('.receipt').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;

        // Reinitialize scripts if needed, or reload page:
        location.reload();
    }
</script>
