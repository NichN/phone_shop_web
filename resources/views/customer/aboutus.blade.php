@extends('Layout.headerfooter')

@section('title', 'About Us')

@section('header')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
<div class="position-relative text-white text-left py-5" style="background: url('{{ asset('image/aboutus.jpg') }}') no-repeat center center; background-size: cover; height: 400px; display: flex; flex-direction: column; align-items: flex-start; justify-content: center;">
  <div class="container">
    <h1 class="fw-bold">Few words about Us</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-start">
        <li class="breadcrumb-item"><a href="{{ route('homepage') }}" class="text-white text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">About Us</li>
      </ol>
    </nav>
  </div>
</div>
@endsection
@section('content')
<div class="container my-3">
  <div class="row">
    <div class="col-md-6 offset-md-3 d-flex flex-column align-items-center text-center">
      <h3 class="fw-bold mb-4 mt-4">Passion for Technology Dedication to You</h3>
      <p class="text-justify" style="line-height: 1.8; text-align: justify;">At TAY MENG, we bring you the latest electronics with quality you can trust and prices you’ll love. From top tech gadgets to everyday essentials, our mission is to make technology accessible and enjoyable for everyone. Shop with confidence at Tay Meng Phone Shop where quality meets innovation!</p>
    </div>
  </div>
</div>

<div class="container my-5 section-spacing">
  <div class="row align-items-center">
    <div class="col-md-6 text-center">
      <img src="{{ asset('image/shop.jpg') }}" class="fixed-img rounded shadow" alt="Shop Us">
    </div>
    <div class="col-md-6">
      <h3 class="fw-bold">Our Mission: Helping Millions of Organizations Grow Better</h3>
      <p class="text-muted">
        We believe not just in growing bigger, but in growing better. And growing better means aligning 
        the success of your own business with the success of your customers. Win-win!
      </p>
    </div>
  </div>
</div>

<div class="container my-5 section-spacing">
  <div class="row align-items-center">
    <div class="col-md-6">
      <h3 class="fw-bold">Our Mission: Helping Millions of Organizations Grow Better</h3>
      <p class="text-muted">
        To provide high-quality, reliable electronics at affordable prices, making technology accessible and enhancing the everyday lives of our customers.
        To be a trusted leader in electronics retail, known for innovation, exceptional customer service, and a commitment to delivering the best in technology.
      </p>
    </div>
    <div class="col-md-6 text-center">
      <img src="{{ asset('image/shop2.jpg') }}" class="fixed-img rounded shadow" alt="Shop Us">
    </div>
  </div>
</div>

  {{-- <div class="position-relative text-overlay-container">
    <img src="{{ asset('picture/baner2.png') }}" class="img-fluid w-100 baner_img" alt="Image feature">
</div>
<div class="container">
<div class="row">
    <div class="col-6 mt-7 mb-5">
        <p>To provide high-quality, reliable electronics at affordable prices, making technology accessible and enhancing the everyday lives of our customers.<br><br>
            To be a trusted leader in electronics retail, known for innovation, exceptional customer service, and a commitment to delivering the best in technology.</p>
      </div>
    <div class="col-6 mt-3 mb-5">	
      <h4 class="fw-bold">Mission and Vision</h4>
    </div>
  </div>
</div> --}}

@endsection

