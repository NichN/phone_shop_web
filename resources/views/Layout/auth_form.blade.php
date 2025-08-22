<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Website')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    {{-- <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #eef2ff;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-radius: 12px;
            --box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
            line-height: 1.6;
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .auth-illustration {
            flex: 1;
            background: gray;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .auth-illustration::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            transform: rotate(30deg);
        }

        .illustration-content {
            max-width: 80%;
            text-align: center;
            z-index: 1;
        }

        .illustration-content h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .illustration-content p {
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .product-carousel {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .carousel-item img {
            border-radius: var(--border-radius);
            object-fit: contain;
            height: 500px;
        }

        .carousel-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
            margin: 0 5px;
        }

        .carousel-indicators .active {
            background-color: white;
            width: 30px;
            border-radius: 10px;

        }

        .auth-form {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: var(--border-radius);
            padding: 2.5rem;
            box-shadow: var(--box-shadow);
        }

        .form-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-logo img {
            height: 50px;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .form-subtitle {
            color: #64748b;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-control {
            height: 50px;
            border-radius: var(--border-radius);
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            width: 100%;
            height: 50px;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: #1e293b;
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
        }

        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .password-toggle {
            cursor: pointer;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #94a3b8;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider::before {
            margin-right: 1rem;
        }

        .divider::after {
            margin-left: 1rem;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: var(--border-radius);
            border: 1px solid #e2e8f0;
            background: white;
            color: var(--dark-color);
            transition: var(--transition);
        }

        .social-btn:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }

        @media (max-width: 992px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-illustration {
                padding: 2rem 1rem;
                border-radius: 0 0 var(--border-radius) var(--border-radius);
            }

            .illustration-content {
                max-width: 100%;
            }

            .product-carousel {
                max-width: 400px;
            }

            .auth-form {
                padding: 2rem 1rem;
            }

            .form-container {
                padding: 2rem;
            }
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .product-carousel {
                max-width: 300px;
            }

            .carousel-item img {
                height: 250px;
            }
        }
    </style> --}}
</head>

<body>
    {{-- <div class="auth-container">
        <div class="auth-illustration">
            <div class="illustration-content">
                <div id="productCarousel" class="carousel slide product-carousel" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('picture/phone1.png') }}" class="d-block w-100" alt="iPhone 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('picture/iphone4.png') }}" class="d-block w-100" alt="iPhone 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('picture/case.png') }}" class="d-block w-100" alt="iPhone Case">
                        </div>
                    </div>

                    <div class="carousel-indicators mt-3">
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                </div>
            </div>
        </div> --}}

    <!-- Form Section -->
    {{-- <div class="auth-form">
            <div class="form-container"> --}}
    {{-- <div class="form-logo">
                    <!-- Replace with your logo -->
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="8" fill="#4361ee"/>
                        <path d="M20 10L25 15H15L20 10Z" fill="white"/>
                        <path d="M25 25L20 30L15 25H25Z" fill="white"/>
                        <path d="M10 20L15 15V25L10 20Z" fill="white"/>
                        <path d="M30 20L25 25V15L30 20Z" fill="white"/>
                    </svg>
                </div> --}}

    {{-- <h1 class="form-title">Welcome Back</h1> --}}
    {{-- <p class="form-subtitle">Please enter your details to sign in</p> --}}

    {{-- @yield('content') --}}

    {{-- <div class="form-footer">
                    <p>Don't have an account? <a href="#">Sign up</a></p>
                </div> --}}
    {{-- </div>
        </div>
    </div> --}}

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-11">
                <div class="card shadow-lg mx-auto">
                    <div class="row g-0 ">
                        <!-- Left Image Section -->
                        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center bg-dark">
                            <div class="w-100 p-3">
                                <div id="productCarousel" class="carousel slide product-carousel"
                                    data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="{{ asset('picture/phone1.png') }}" class="d-block w-100"
                                                alt="iPhone 1">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="{{ asset('picture/iphone4.png') }}" class="d-block w-100"
                                                alt="iPhone 2">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="{{ asset('picture/case.png') }}" class="d-block w-100"
                                                alt="iPhone Case">
                                        </div>
                                    </div>

                                    <div class="carousel-indicators ">
                                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0"
                                            class="active" aria-current="true" aria-label="Slide 1"></button>
                                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1"
                                            aria-label="Slide 2"></button>
                                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="2"
                                            aria-label="Slide 3"></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Form Section -->
                        <div class="auth-form">
                            <div class="form-container">
                                <!-- Logo -->
                                <div class="auth-logo text-center">
                                    <img src="{{ asset('image/tay_meng_logo.jpg') }}" alt="Logo" height="100">
                                </div>
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password toggle functionality
        document.querySelectorAll('.password-toggle').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });

        // Form animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.form-label').style.transform = 'translateY(-5px)';
                this.parentElement.querySelector('.form-label').style.fontSize = '0.8rem';
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.querySelector('.form-label').style.transform = 'none';
                    this.parentElement.querySelector('.form-label').style.fontSize = '1rem';
                }
            });
        });
    </script>
</body>

</html>
