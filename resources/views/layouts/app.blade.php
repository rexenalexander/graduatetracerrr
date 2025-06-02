<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        html,
        body {
            width: 99%;
            /* overflow-x: hidden; */
            margin: 0;
        }
        .text-xs {
            font-size: 10px !important;
        }
/* 
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        } */

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .navbar-custom {
            animation: fadeIn 0.5s ease-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .nav-item {
            animation: slideIn 0.5s ease-out;
            animation-fill-mode: both;
        }

        .nav-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .nav-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .nav-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .logout-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
        }

        .logout-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .logout-btn:hover::after {
            width: 100%;
        }

        .card {
            animation: fadeIn 0.5s ease-out;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            /* transform: translateY(-5px); */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .toast {
            animation: slideIn 0.3s ease-out;
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .navbar-nav {
                padding: 1rem 0;
            }

            .nav-item {
                padding: 0.5rem 0;
            }
        }

        #sidebar {
            min-width: 100%;
            min-height: 100vh;
            transition: all 0.35s ease-in-out;
            height: 100vh;
            overflow-y: auto; /* <--- enables scroll if needed */
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        @media (max-width: 768px) {

            #sidebar.active {
                margin-left: 0;
            }

            .dropdown-menu {
                position: static !important;
                transform: none !important;
                margin-top: 0 !important;
            }
        }

        .sidebar-link {
            padding: 10px 15px;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
            border-radius: 5px;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .auth-navbar {
            background: rgba(0, 0, 0, 0.1) !important;
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            /* Reduced from 1000 */
            height: 70px;
            display: flex;
            align-items: center;
        }

        .auth-navbar .container {
            max-width: 1200px;
            width: auto;
            /* Remove full-width container in auth navbar */
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            height: 100%;
            justify-content: flex-start;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0.5rem 0;
            margin: 0;
            z-index: 10;
            /* Match navbar z-index */
            width: auto;
            /* Limit width to content */
        }

        .navbar-logo {
            height: 48px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
            max-width: 200px;
            /* Limit logo size */
        }

        .navbar-brand:hover .navbar-logo {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .auth-navbar {
                height: 60px;
            }

            .navbar-logo {
                height: 38px;
            }

            .auth-navbar .container {
                padding: 0 1rem;
            }
        }

        .auth-footer {
            background: rgba(0, 0, 0, 0.85) !important;
            backdrop-filter: blur(10px);
            color: white;
            padding: 3rem 0 1.5rem;
            position: relative;
            width: 100%;
            margin-top: auto;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0;
        }

        .navbar-logo {
            height: 40px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2.5rem;
            margin-left: auto;
            height: 100%;
        }

        .nav-link {
            color: white !important;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            opacity: 0.85;
        }

        .nav-link:hover,
        .nav-link.active {
            opacity: 1;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .auth-footer {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            color: white;
            padding: 3rem 0 1.5rem;
            position: relative;
            width: 100%;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-section {
            flex: 1;
            min-width: 250px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .footer-section h5 {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin: 0;
        }

        .social-links {
            display: flex;
            gap: 1.2rem;
            margin-top: 0.5rem;
        }

        .social-links a {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: white;
            transform: translateY(-3px);
        }

        .footer-bottom {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .auth-navbar {
                height: 56px;
            }

            .navbar-logo {
                height: 32px;
            }

            .container {
                padding: 0 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .nav-link {
                font-size: 0.85rem;
                padding: 0.4rem 0.8rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }

            .footer-section p,
            .footer-section a {
                justify-content: center;
            }

            .social-links {
                justify-content: center;
            }

            .auth-footer {
                padding: 2rem 0 1rem;
            }

            .footer-bottom {
                margin-top: 2rem;
            }
        }

        .navbar-dark {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
        }
        #toggleSidebar {
            background: #2a5298 !important;
            display: none;
        }

        .bg-success {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
        }

        .btn-primary {
            background: linear-gradient(to right, #1e3c72, #2a5298) !important;
            border: none;
        }

        /* Department Cards Styling */
        .dept-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .dept-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dept-icon {
            transition: all 0.3s ease;
        }

        .dept-card:hover .dept-icon {
            transform: scale(1.1);
        }

        body.auth-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        @media (max-width: 768px) {
            .navbar-nav {
                padding: 0.5rem 0;
                /* Reduced from 1rem */
            }

            .nav-item {
                padding: 0.25rem 0;
                /* Reduced from 0.5rem */
            }

            .auth-navbar {
                height: 36px;
            }

            .navbar-logo {
                height: 20px;
            }

            .auth-nav-links {
                gap: 0.8rem;
            }

            .auth-nav-link {
                font-size: 0.8rem;
                padding: 0.2rem 0.6rem;
            }
        }

        .auth-navbar {
            background: rgba(0, 0, 0, 0.3) !important;
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            /* Reduced from 1000 */
            height: 60px;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateY(-2px);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            z-index: 10;
            /* Match navbar z-index */
            width: auto;
            /* Limit width to content */
        }

        .navbar-logo {
            height: 35px;
            width: auto;
            transition: transform 0.3s ease;
            max-width: 200px;
            /* Limit logo size */
        }

        .auth-footer {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            color: white;
            padding: 3rem 0 1rem;
            position: relative;
            z-index: 1;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h5 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: 0.3s ease;
        }

        .footer-section a:hover {
            color: white;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .social-links a:hover {
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width: 768px) {
            .auth-navbar {
                height: 50px;
            }

            .navbar-logo {
                height: 28px;
            }

            .nav-links {
                gap: 1rem;
            }

            .nav-link {
                font-size: 0.85rem;
                padding: 0.4rem 0.8rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .social-links {
                justify-content: center;
            }

            .footer-section a:hover {
                transform: none;
            }
        }

        .auth-navbar {
            background: rgba(0, 0, 0, 0.1) !important;
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            /* Reduced from 1000 */
            height: 60px;
        }

        .auth-navbar.scrolled {
            background: rgba(0, 0, 0, 0.85) !important;
        }

        .toggle-form,
        .toggle-form:hover,
        .toggle-form:focus,
        .toggle-form:active {
            cursor: pointer !important;
            color: white !important;
            text-decoration: underline;
            transition: all 0.3s ease;
            display: inline-block;
            pointer-events: auto !important;
            z-index: 30;
            /* Highest z-index for clickable elements */
            position: relative;
        }

        .form-box {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.3s ease;
            position: absolute;
            z-index: 25;
            /* Higher than form-container */
        }

        .form-box.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Fix form interactions */
        .auth-page {
            position: relative;
            z-index: 1;
        }

        .form-container {
            position: relative;
            z-index: 20;
            /* Higher than navbar */
        }

        .toggle-form {
            position: relative;
            z-index: 30;
            /* Highest z-index for clickable elements */
            cursor: pointer !important;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            margin-top: 0;
            padding-top: 60px;
        }

        /* Fix animations and interactions */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .form-container {
            animation: fadeInScale 0.5s ease-out;
            position: relative;
            z-index: 20;
            /* Higher than navbar */
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            padding-top: 60px;
            min-height: 100vh;
            width: 100%;
            display: flex;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            margin-top: 0;
        }

        .toggle-form {
            position: relative;
            z-index: 30;
            /* Highest z-index for clickable elements */
            cursor: pointer !important;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        /* Remove any pointer-events interference */
        .form-box,
        .form-box.active,
        .form-container,
        .forms-wrapper {
            pointer-events: auto !important;
        }
        .mysidebar {
            position: fixed;
            transition: all 0.35s ease-in-out;
            z-index: 99;
        }
        #content {
            margin-left: 250px;
        }
        
        .mysidebar.hidden {
            display: none !important;
        }
        #content.full-width {
            margin-left: 0;
        }
        
        @media (max-width: 768px) {
            #toggleSidebar{
                display: block;
            }
            .mysidebar.auto-hidden {
                display: none !important;
            }
            #content {
                margin-left: 0 !important;
            }  
        }

    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@guest

    <body class="auth-page" style="padding-top: 60px;">
        <nav class="navbar navbar-dark auth-navbar">
            <div class="container">
                <!-- Logo container with limited width -->

            </div>
        </nav>
        <main>
            @yield('content')
        </main>
        <footer class="auth-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h5>Contact Us</h5>
                        <p><i class="bi bi-geo-alt"></i> City of Batac, Ilocos Norte, Philippines</p>
                        <p><i class="bi bi-telephone"></i> (077) 792-3931</p>
                        <p><i class="bi bi-envelope"></i> op@mmsu.edu.ph</p>
                    </div>
                    <div class="footer-section">
                        <h5>Quick Links</h5>
                        <a href="https://www.mmsu.edu.ph/about/directory">About Us</a>
                        <a href="https://www.facebook.com/MMSUofficial">Programs</a>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} MMSU College of Engineering. All rights reserved.</p>
                </div>
            </div>
        </footer>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
@else

    <body>
        @auth
            <div class="row">
                <div class="mysidebar" style="min-height: 100vh; max-width: 250px">
                    @if(auth()->user()->email === 'admin@gmail.com')
                        <nav id="sidebar" class="bg-dark d-flex flex-column">
                            <div class="p-4 flex-grow-1">
                                <div class="d-flex align-items-center mb-4">
                                    <img src="https://coe.mmsu.edu.ph/images/logo/logo_dn.png" alt="MMSU Logo" class="navbar-logo">
                                </div>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="{{ route('admin.graduates') }}"
                                            class="sidebar-link text-nowrap {{ request()->routeIs('admin.graduates') ? 'active' : '' }}">
                                            <i class="bi bi-people"></i> Manage Graduates
                                        </a>
                                    </li>
                                    
                                    <li class="mb-2">
                                        <a href="{{ route('admin.employers') }}"
                                            class="sidebar-link text-nowrap {{ request()->routeIs('admin.employers') ? 'active' : '' }}">
                                            <i class="bi bi-people"></i> Manage Employers
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a href="{{ route('admin.export') }}" class="sidebar-link text-nowrap">
                                            <i class="bi bi-download"></i> Export Data
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="p-4 border-top border-secondary mb-5">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person-circle text-white me-2"></i>
                                    <span class="text-white text-nowrap">{{ Auth::user()->name }}</span>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="sidebar-link w-100 btn btn-link text-start text-white p-2">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </nav>
                    @else
                        <nav id="sidebar" class="bg-dark d-flex flex-column">
                            <div class="p-4 flex-grow-1">
                                <div class="d-flex align-items-center mb-4">
                                    <img src="https://coe.mmsu.edu.ph/images/logo/logo_dn.png" alt="MMSU Logo" class="navbar-logo">
                                    <h5 class="text-white mb-0"></h5>
                                </div>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="{{ route('dashboard') }}"
                                            class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard
                                        </a>
                                    </li>
                                    @if(auth()->user()->role == 2)
                                        <li class="mb-2">
                                            <a href="{{ route('survey.employers') }}"
                                                class="sidebar-link text-nowrap {{ request()->routeIs('survey.employers') ? 'active' : '' }}">
                                                <i class="bi bi-people"></i> Manage Surveys
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->role == 1)
                                    <li class="mb-2">
                                        <a href="{{ route('graduates.create') }}"
                                            class="sidebar-link {{ request()->routeIs('graduates.create') ? 'active' : '' }}">
                                            <i class="bi bi-file-text"></i> Fill Up Survey
                                        </a>
                                    </li>
                                    @elseif(auth()->user()->role == 2)
                                    <li class="mb-2">
                                        <a href="{{ route('employers.create') }}"
                                            class="sidebar-link {{ request()->routeIs('employers.create') ? 'active' : '' }}">
                                            <i class="bi bi-file-text"></i> Fill Up Survey
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <!-- Logout Section -->
                            <div class="p-4 border-top border-secondary mb-5">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person-circle text-white me-2"></i>
                                    <span class="text-white">{{ Auth::user()->name }}</span>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="sidebar-link w-100 btn btn-link text-start text-white p-2">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </nav>
                    @endif
                </div>

                <div class="col">
                    <div id="content" class="pt-2 pb-3">
                        <div class="d-flex justify-content-end">
                            <button id="toggleSidebar" class="btn text-white navbar-toggler me-4 text-lg px-2 mb-2 rounded" >
                                <i class="bi bi-list"></i>
                            </button>
                        </div>
                        <main class="pt-3">
                            @yield('content')
                        </main>
                    </div>
                </div>

            </div>
        @else
            <!-- Auth Navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark auth-navbar">
                <div class="container">
                    <a class="navbar-brand" href="/">
                        <img src="https://coe.mmsu.edu.ph/images/logo/logo_dn.png" alt="MMSU Logo" class="navbar-logo">

                    </a>
                </div>
            </nav>
            <main class="py-4">
                @yield('content')
            </main>
        @endauth

        <!-- Success Toast -->
        @if(session('success'))
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div class="toast show" role="alert">
                    <div class="toast-header bg-success text-white">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Toast -->
        @if(session('error'))
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div class="toast show" role="alert">
                    <div class="toast-header bg-danger text-white">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong class="me-auto">Error</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @stack('scripts')

        <script>
            window.addEventListener('scroll', function () {
                const navbar = document.querySelector('.auth-navbar');
                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            document.addEventListener('DOMContentLoaded', function () {
                const toggleBtn = document.getElementById('toggleSidebar');
                const sidebar = document.querySelector('.mysidebar');
                const content = document.getElementById('content');

                function handleResize() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.add('auto-hidden');
                        sidebar.classList.add('hidden');
                        content.classList.add('full-width');
                    } else {
                        sidebar.classList.remove('auto-hidden');
                        sidebar.classList.remove('hidden');
                        content.classList.remove('full-width');
                    }
                }

                toggleBtn.addEventListener('click', function () {
                    const isHidden = sidebar.classList.contains('hidden');

                    if (isHidden) {
                        // Show sidebar
                        sidebar.classList.remove('hidden');
                        sidebar.classList.remove('auto-hidden'); // <- critical
                        content.classList.remove('full-width');
                    } else {
                        // Hide sidebar
                        sidebar.classList.add('hidden');
                        content.classList.add('full-width');

                        // Add auto-hidden only if small screen
                        if (window.innerWidth <= 768) {
                            sidebar.classList.add('auto-hidden');
                        }
                    }
                });

                window.addEventListener('resize', handleResize);
                handleResize(); // Initial check
            });


        </script>
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
@endguest

</html>