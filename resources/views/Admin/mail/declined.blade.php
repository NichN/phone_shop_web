<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Declined</title>
</head>
<body>
    <p>Hello {{ $order->customer_name ?? 'Customer' }},</p>

    <p>We regret to inform you that your order <strong>#{{ $order->order_num }}</strong> has been declined.</p>

    <p>If you have questions, please contact our support team.</p>

    <p>Thank you for understanding.</p>
</body>
</html>
