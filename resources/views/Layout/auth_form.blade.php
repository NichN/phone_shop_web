<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
 <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
 <link href="{{ asset('css/mainstyle.css')}}" rel="stylesheet">
</head>
<body>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-6 background rounded">
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="{{ asset('picture/phone1.png') }}" class="d-block img-fluid container mt-8" alt="Image 1">
            </div>
            <div class="carousel-item">
              <img src="{{ asset('picture/iphone4.png') }}" class="d-block img-fluid container mt-8" alt="Image 2">
            </div>
            <div class="carousel-item">
              <img src="{{ asset('picture/case.png') }}" class="d-block img-fluid container mt-8" alt="Image 3">
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-3 border bg-light">
                @yield('content')
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/auth.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
