@extends('Layout.headerfooter')

@section('title', 'Privacy Policy')

@section('header')
    @parent
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection
@section('content')
<div class="container mt-4">
    <p class="mb-4">
        <strong>Tay Meng Mobile Shop</strong> respects your privacy and is committed to protecting your personal information.
        This Privacy Policy explains how we collect, use, and protect your data when you visit our website or contact us.
    </p>

    <div class="mb-5">
        <h5 class="fw-bold text-uppercase mb-3">1. What Information We Collect</h5>
        <p>We may collect the following information when you use our site:</p>
        <ul class="list-unstyled ps-3">
            <li>• Full Name</li>
            <li>• Phone Number</li>
            <li>• Email Address</li>
            <li>• Shipping Address</li>
            <li>• Device or browser information</li>
        </ul>
    </div>

    <div class="mb-5">
        <h5 class="fw-bold text-uppercase mb-3">2. How We Use Your Information</h5>
        <p>Your information is used to:</p>
        <ul class="list-unstyled ps-3">
            <li>• Confirm and deliver your orders</li>
            <li>• Respond to inquiries and support</li>
            <li>• Improve our website experience</li>
        </ul>
    </div>

    <div class="mb-5">
        <h5 class="fw-bold text-uppercase mb-3">3. Data Security</h5>
        <p>
            We take steps to protect your data from unauthorized access, including secure servers and internal access controls. However, no system is 100% secure.
        </p>
    </div>

    <div class="mb-5">
        <h5 class="fw-bold text-uppercase mb-3">4. Cookies</h5>
        <p>
            This website may use cookies to save your preferences and improve performance. You can disable cookies in your browser settings if you prefer.
        </p>
    </div>

    <div class="mb-5">
        <h5 class="fw-bold text-uppercase mb-3">5. Your Rights</h5>
        <p>
            You may contact us to review, correct, or delete any personal data we have collected about you.
        </p>
    </div>

    <div class="mb-5">
        <h5 class="fw-bold text-uppercase mb-3">6. Contact Information</h5>
        <p>
            For questions about this Privacy Policy, contact us via:
        </p>
        <ul class="list-unstyled ps-3">
            <li>Email: <a href="mailto:info@taymengmobileshop.com" class="text-danger">info@taymengmobileshop.com</a></li>
            <li>Phone: 096 841 2222 / 076 3333 288 / 031 3333 288</li>
            <li>Facebook: <a href="https://www.facebook.com/TayMeng13?mibextid=wwXIfr" class="text-danger">Tay Meng Mobile Shop</a></li>
        </ul>
    </div>
</div>
@endsection
@section('styles')
    <style>
    .terms-header img {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.text-shadow {
    text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.6);
}

.text-justify {
    text-align: justify;
}

</style>
@endsection