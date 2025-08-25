<h2>Hello {{ $order->guest_name ?? 'Customer' }},</h2>

<p>Your payment for Order #{{ $order->order_num}} has been confirmed.</p>

<p>Status: {{ ucfirst($order->status) }}</p>

<p>Thank you for your purchase!</p>
