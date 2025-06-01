<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Website')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/mainstyle.css')}}" rel="stylesheet"> --}}
</head>
<style>
  body {
      background: #ececec;
  }

  .box-area {
      max-width: 100%;
      width: 100%;
      min-height: 650px;
      overflow: hidden;
  }

  .left-pane {
      background-color: #3c3434;
      padding: 2rem 1rem;
  }

  .carousel-indicators {
      position: static;
      margin-top: 1rem;
  }

  @media (max-width: 768px) {
      .box-area {
          flex-direction: column;
          height: auto;
      }

      .left-pane {
          padding-bottom: 5rem;
      }

      .register-link {
          position: relative;
          bottom: 0;
          left: 0;
          margin-top: 1rem;
          text-align: center;
          width: 100%;
      }
  }
</style>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
      <div class="row w-100 justify-content-center">
        <div class="col-lg-10 col-md-11 col-12 p-0 bg-white shadow d-flex flex-wrap box-area">
            <!-- Left Pane -->
            <div class="col-lg-6 col-md-6 col-12 left-pane d-flex justify-content-center align-items-center flex-column position-relative">
                <div id="productCarousel" class="carousel slide w-100" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('picture/phone1.png') }}" class="d-block mx-auto img-fluid" alt="iPhone 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('picture/iphone4.png') }}" class="d-block mx-auto img-fluid" alt="iPhone 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('picture/case.png') }}" class="d-block mx-auto img-fluid" alt="iPhone 3">
                        </div>
                    </div>

                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                </div>
            </div>

            <!-- Right Pane -->
            <div class="col-lg-6 col-md-6 col-12 right-pane p-4 d-flex align-items-center justify-content-center">
                <div class="w-100">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container mt-3">
        <div class="row">
            <div class="col-md-6 background rounded">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('picture/phone1.png') }}" class="d-block img-fluid container mt-8"
                                alt="Image 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('picture/iphone4.png') }}" class="d-block img-fluid container mt-8"
                                alt="Image 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('picture/case.png') }}" class="d-block img-fluid container mt-8"
                                alt="Image 3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="next">
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
    </div> --}}
    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
