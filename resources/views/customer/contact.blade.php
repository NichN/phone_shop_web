@extends('Layout.headerfooter')

@section('title', 'About Us')

@section('header')
<div class="position-relative text-overlay-container">
    <div class="text-end picture">
        <img src="{{ asset('picture/contact1.png') }}" class="img-fluid w-100 baner_img" alt="contact Banner">
    </div>
    <div class="overlay-text position-absolute top-0 mt-10 ms-12 mb-2 text-secondary">
        <h1 class="fw-bold">Contact Us</h1>
        <p>We value your feedback and are here to assist you<br> with any questions or concerns. </p>
    </div>
</div>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-md-center ROW">
      <div class="col-lg-2 backround COL">
        <div class="container">
            <i class="fa-solid fa-location-arrow icon"></i>
            <br>
            <h4 class="fw-normal">Location</h4>
        </div>
      </div>
      <div class="col-lg-2 bg-secondary COL">
        <div class="container">
            <i class="fa-solid fa-envelope icon text-white"></i>
            <br>
            <h4 class="fw-normal text-white">Email</h4>
      </div>
    </div>
      <div class="col-lg-2 backround COL">
        <div class="container">
            <i class="fa-solid fa-phone icon"></i>
            <br>
            <h4 class="fw-normal">Phone</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="container mt-4">
    <div class="row">
      <div class="col">
        <h4 class="fw-normal text-center fw-bold">Send Us a Message</h4>
        <form class="mt-5">
            <div class="row">
              <div class="col">
                <label class="fw-bold">Your Name</label>
                <input type="text" class="form-control border-secondary" placeholder="Enter Your Name" name="Name">
              </div>
              <div class="col">
                <label class="fw-bold">Phone Number</label>
                <input type="text" class="form-control border-secondary" placeholder="Enter Phone Nnumber" name="Phone">
              </div>
              <div class="mb-3 mt-3">
                <label for="exampleInputEmail1" class="form-label fw-bold">Email address</label>
                <input type="email" class="form-control border-secondary" id="exampleInputEmail1" aria-describedby="emailHelp"  placeholder="Enter Your Emial">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
              </div>
            </div>
            <div class="mb-3">
            <label for="comment" class="fw-bold">Message:</label>
                <textarea class="form-control border-secondary" rows="5" id="comment" name="text"></textarea>
            </div>
            <button type="submit" class="btn btn-secondary">Sent Message</button>
          </form>
      </div>
      <div class="col">
            <h4 class="fw-normal text-center fw-bold">Location</h4>
            <p class="fw-bold">78 Krala Haom Kong St. (118), Phnom Penh</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125069.37724563181!2d104.86133282261211!3d11.593516216161756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3109513a28530555%3A0x283e659a32b64e83!2sTay%20Meng%20Mobile%20Center!5e0!3m2!1sen!2skh!4v1737611391553!5m2!1sen!2skh" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</div>
@endsection