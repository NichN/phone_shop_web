@extends('Layout.headerfooter')

@section('title', 'About Us')

@section('header')
<div class="position-relative text-overlay-container">
    <img src="{{ asset('picture/baner1.png') }}" class="img-fluid w-100 baner_img" alt="Image feature">
    <div class="overlay-text position-absolute top-0 mt-8 start-0 ms-3 mb-2 text">
        <h1 class="fw-bold">Welcome to Our Shop</h1>
        <p class="fs-4 mx-3">A Few Words About Us</p>
    </div>
</div>
@endsection
@section('content')
<div class="container">
    <!-- Columns are always 50% wide, on mobile and desktop -->
    <div class="row">
      <div class="col-6 mt-3 mb-5 ">	
        <h4 class="fw-bold">Passion for Technology <br>
            Dedication to You</h4>
      </div>
      <div class="col-6 mt-7 mb-5">
        <p>At TAY MENG, we bring you the latest electronics with quality you can trust and prices you’ll love. From top tech gadgets to everyday essentials, our mission is to make technology accessible and enjoyable for everyone. Shop with confidence at [Shop Name] where quality meets innovation!</p>
      </div>
    </div>
  </div>
  <div class="position-relative text-overlay-container">
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
</div>
</div>
</div
@endsection

