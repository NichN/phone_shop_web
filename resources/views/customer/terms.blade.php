@extends('Layout.headerfooter')

@section('title', 'Terms of Service')

@section('header')
    @parent
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('content')
    {{-- <div class="terms-header text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold text-shadow">Terms of Service</h1>
        </div>
    </div> --}}

    <div class="position-relative text-overlay-container">
        <div class="banner-container">
            <img src="{{ asset('image/banner.jpg') }}" alt="terms Banner" class="img-fluid w-100"
                style="max-height: 300px; object-fit: cover;">
            <div class="banner-text position-absolute top-50 start-50 translate-middle text-center text-dark">
                <h4 class="fw-bold">Terms of Service</h4>
                {{-- <p class="lead">Find answers to common questions about our services</p> --}}
            </div>
        </div>
    </div>

    <div class="container py-5 text-justify">
        <hr class="border-2 border-primary mb-4" style="width: auto;">

        <p>
            Welcome to <strong>Tay Meng Mobile Shop</strong>. We are a trusted house-based retailer offering genuine mobile
            phones and accessories from top brands such as
            <strong>Apple, Samsung, Vivo, Oppo, and Xiaomi</strong>. These Terms of Service govern your use of our website
            and any purchases made through it.
            By accessing or using our website, you agree to be bound by these Terms.
        </p>

        <h5 class="mt-4 fw-bold text-uppercase">1. Products and Services</h5>
        <p>
            All products listed on our website are either new or officially refurbished and come with a standard
            manufacturer or store warranty.
            We aim to provide accurate product descriptions, images, and specifications, but we do not guarantee that all
            details are error-free.
        </p>

        <h5 class="mt-4 fw-bold text-uppercase">2. Account Registration</h5>
        <p>
            You may browse our website without an account, but some features (such as placing orders) may require
            registration.
            You are responsible for keeping your account information secure. We reserve the right to suspend or terminate
            accounts for any misuse.
        </p>

        <h5 class="mt-4 fw-bold text-uppercase">3. Orders, Payments & Delivery</h5>
        <p>
            Orders are confirmed only after payment has been received. We accept cash on delivery (COD) and bank transfer in
            Cambodia.
            Delivery within the city is typically completed within 1–3 business days. Delivery times may vary depending on
            location and product availability.
        </p>

        <h5 class="mt-4 fw-bold text-uppercase">4. Returns and Warranty</h5>
        <p>
            We offer a limited return and exchange policy for defective or damaged items reported within 3 days of delivery.
            Warranty periods vary by brand and product. Please keep your invoice and contact us for any warranty claims.
        </p>

        <h5 class="mt-4 fw-bold text-uppercase">5. Age Restrictions</h5>
        <p>
            Users under the age of 18 must obtain permission from a parent or guardian before using our services.
            Children under 13 years old are not permitted to register or place orders without adult supervision.
        </p>

        <h5 class="mt-4 fw-bold text-uppercase">6. Limitation of Liability</h5>
        <p>
            Tay Meng Mobile Shop shall not be liable for any indirect, incidental, or consequential damages resulting from
            the use or inability to use our website,
            or from any products purchased through us beyond the value of the product itself.
        </p>

        <h5 class="mt-4 fw-bold text-uppercase">7. Changes to Terms</h5>
        <p>
            We reserve the right to modify these Terms at any time. Any changes will be updated on this page with a new
            effective date.
            Continued use of the website after such changes implies acceptance.
        </p>

        <h5 class="mt-4 fw-bold text-uppercase">8. Contact Information</h5>
        <p>
            For questions about these Terms of Service or any other concerns, please contact us:
        </p>
        <ul>
            <li>Email: <a href="mailto:info@taymengmobileshop.com" class="text-danger">info@taymengmobileshop.com</a></li>
            <li>Phone: 096 841 2222 / 076 3333 288 / 031 3333 288</li>
            <li>Facebook Page: <a href="https://www.facebook.com/TayMeng13?mibextid=wwXIfr" class="text-danger">Tay Meng
                    Mobile Shop</a></li>
            <li>Location: House 78E0, Street 13, Phnom Penh, Cambodia</li>
        </ul>
    </div>
@endsection

{{-- @section('styles')
    <link rel="stylesheet" href="{{ asset('css/terms.css') }}">
    <style>
        .terms-header {
            background-image: url('/image/HeaderPhone.jpg');
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
@endsection --}}
