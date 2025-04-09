<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/profiledetail.css') }}">
</head>
<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <div class="sidebar-logo">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('image/airpods.jpg') }}" alt="User Profile Image" class="rounded-circle" width="40" height="40">
                        <span class="text-white fw-semibold">Youheang</span>
                    </div>
                </div>
            </div>            
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="{{ route('profile') }}" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('email') }}" class="sidebar-link">
                        <i class="bi bi-envelope"></i>
                        <span>Email</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('password') }}" class="sidebar-link">
                        <i class="bi bi-three-dots"></i>
                        <span>Password</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('address') }}" class="sidebar-link">
                        <i class="bi bi-geo-alt"></i>
                        <span>Address</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-escape"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <div class="main p-3">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
</body>
</html>
