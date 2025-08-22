{{-- @extends('Layout.headerfooter')

@section('title', 'About Us')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}"> --}}

    {{-- <div class="position-relative text-white text-left py-5"
        style="background: url('{{ asset('image/taymeng.jpg') }}') no-repeat center center; background-size: cover; height: 400px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
        <div class="container">
            <h1 class="fw-bold">Few words about Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-start">
                    <li class="breadcrumb-item"><a href="{{ route('homepage') }}"
                            class="text-white text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">About Us</li>
                </ol>
            </nav>
        </div>
    </div> --}}

    {{-- <div class="position-relative text-white text-left" style="margin:0; padding:0;">
        <img src="{{ asset('image/taymeng.jpg') }}" alt="About Us" class="img-fluid w-100 d-block">
    </div>

@endsection
@section('content')
    <div class="container my-3">
        <div class="row">
            <div class="col-12 d-flex flex-column align-items-center text-center">
                <h4 class="mb-4 mt-4 fw-bold">About Us – Tay Meng Mobile Shop</h4>
                <p class="text-muted" style="line-height: 1.8; font-size: 1.05rem; text-align: justify;">
                    Established in 2018, Tay Meng Mobile Shop is a trusted mobile phone sales and repair
                    service located at #78E0, Street 13, Phsar Kondal I, Daun Penh, Phnom Penh.
                    Our shop is strategically positioned in the heart of Phnom Penh, making it
                    easily accessible for customers seeking quality mobile solutions. Since our
                    inception, we have been committed to providing reliable and efficient services,
                    ensuring customer satisfaction with every visit.
                </p>
            </div>
        </div>
    </div>

    <div class="container my-3 section-spacing">
        <div class="row align-items-center">
            <div class="col-md-6 text-center">
                <img src="{{ asset('image/Taymengshop.png') }}" class="fixed-img rounded shadow" alt="Shop Us">
            </div>
            <div class="col-md-6">
                <h4>Why Choose Us</h4>
                <p class="text-muted" style="line-height: 1.8; font-size: 1rem; text-align: justify;">
                    At Taymeng Shop, we provide top-quality smartphones and accessories at fair
                    prices, combined with reliable service, so every customer can shop with
                    confidence. Our team is passionate about technology and is always ready to
                    help you find the perfect device to match your needs. We carefully select our
                    products to ensure durability and performance, and we stay updated with the
                    latest trends in mobile technology. Customer satisfaction is our top
                    priority, and we strive to create a seamless shopping experience, whether
                    you visit our store in person or shop online.
                </p>
            </div>
        </div>
    </div>


    <div class="container my-5 section-spacing">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h4>Phone Repair Services</h4>
            <p class="text-muted" style="line-height: 1.8; font-size: 1rem; text-align: justify;">
                At Taymeng Shop, we provide professional phone repair services to keep your devices running smoothly.
                From screen replacements to battery repairs and software troubleshooting, our skilled technicians ensure
                your smartphone is restored quickly and efficiently. We use high-quality parts and follow strict
                procedures to guarantee reliable results, giving you peace of mind and extending the life of your
                device. Your satisfaction is our priority, and we are committed to delivering fast, trustworthy service
                every time.
            </p>
        </div>
        <div class="col-md-6 text-center">
            <img src="{{ asset('image/fix.jpg') }}" class="fixed-img rounded shadow" alt="Phone Repair">
        </div>
    </div>
</div>
@endsection --}}

@extends('Layout.headerfooter')

@section('title', 'About Us')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
    <div class="position-relative text-white text-left">
        <img src="{{ asset('image/taymeng.jpg') }}" alt="About Us" class="img-fluid w-100 d-block">
    </div>
@endsection

@section('content')
    {{-- About Us Section --}}
    <div class="container my-4">
        <div class="row">
            <div class="col-12 text-center">
                <h4 class="mb-4 fw-bold">About Us – Tay Meng Mobile Shop</h4>
                <p class="text-muted" style="line-height: 1.8; font-size: 1.05rem; text-align: justify;">
                    Established in 2018, Tay Meng Mobile Shop is a trusted mobile phone sales and repair
                    service located at #78E0, Street 13, Phsar Kondal I, Daun Penh, Phnom Penh.
                    Our shop is strategically positioned in the heart of Phnom Penh, making it
                    easily accessible for customers seeking quality mobile solutions. Since our
                    inception, we have been committed to providing reliable and efficient services,
                    ensuring customer satisfaction with every visit.
                </p>
            </div>
        </div>
    </div>

    {{-- Why Choose Us --}}
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6 col-12 text-center mb-3 mb-md-0">
                <img src="{{ asset('image/Taymengshop.png') }}" class="img-fluid rounded shadow" alt="Shop Us">
            </div>
            <div class="col-md-6 col-12">
                <h4>Why Choose Us</h4>
                <p class="text-muted" style="line-height: 1.8; font-size: 1rem; text-align: justify;">
                    At Taymeng Shop, we provide top-quality smartphones and accessories at fair
                    prices, combined with reliable service, so every customer can shop with
                    confidence. Our team is passionate about technology and is always ready to
                    help you find the perfect device to match your needs. We carefully select our
                    products to ensure durability and performance, and we stay updated with the
                    latest trends in mobile technology. Customer satisfaction is our top
                    priority, and we strive to create a seamless shopping experience, whether
                    you visit our store in person or shop online.
                </p>
            </div>
        </div>
    </div>

    {{-- Phone Repair Services --}}
    <div class="container my-5">
        <div class="row align-items-center flex-md-row flex-column-reverse">
            <div class="col-md-6 col-12 mt-3 mt-md-0">
                <h4>Phone Repair Services</h4>
                <p class="text-muted" style="line-height: 1.8; font-size: 1rem; text-align: justify;">
                    At Taymeng Shop, we provide professional phone repair services to keep your devices running smoothly.
                    From screen replacements to battery repairs and software troubleshooting, our skilled technicians ensure
                    your smartphone is restored quickly and efficiently. We use high-quality parts and follow strict
                    procedures to guarantee reliable results, giving you peace of mind and extending the life of your
                    device. Your satisfaction is our priority, and we are committed to delivering fast, trustworthy service
                    every time.
                </p>
            </div>
            <div class="col-md-6 col-12 text-center">
                <img src="{{ asset('image/fix.jpg') }}" class="img-fluid rounded shadow" alt="Phone Repair">
            </div>
        </div>
    </div>
@endsection

