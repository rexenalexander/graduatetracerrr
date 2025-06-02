@extends('layouts.app')

@section('content')
    <div class="auth-wrapper">
        <div class="background"></div>

        <div class="container">
            <div class="branding-section">
                <h2 class="logo">
                    <img src="https://www.mmsu.edu.ph/mmsu_logo/mmsu_logo.png" alt="MMSU Logo" class="mmsu-logo">
                    <span class="mmsu-text">Mariano Marcos State University</span>
                </h2>
                <div class="welcome-text">
                    <h2>Welcome Back! <br><span>Computer Engineering Alumni</span></h2>
                    <p>Sign up to continue your journey.</p>
                </div>
            </div>

            <div class="form-container">
                <div class="forms-wrapper">
                    <div class="form-box login active">
                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                            @csrf
                            <h2>Sign In</h2>
                        
                            <div class="input-box">
                                <span class="icon"><i class="bi bi-envelope"></i></span>
                                <input type="text" name="email" value="{{ old('email') }}" placeholder=" " autocomplete="off" required>
                                <label for="email">Username</label>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div class="input-box">
                                <span class="icon"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" placeholder=" " required>
                                <label>Password</label>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Terms Checkbox with Modal Trigger -->
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="termsTriggerCheck" data-bs-toggle="modal" data-bs-target="#termsModal">
                                <label class="form-check-label text-white" for="termsTriggerCheck">
                                    I agree to the <span class="text-primary text-decoration-underline" role="button">Terms and Conditions</span>
                                </label>
                            </div>
                        
                            <p>
                                <a href="{{ route('forgot-pass') }}" class="text-warning text-underline text-sm">Forgot Password?</a>
                            </p>
                        
                            <button type="submit" id="signinBtn" class="btn" disabled>Sign In</button>
                        
                            @if(!request()->is('admin*'))
                                <div class="register-link">
                                    <p>Don't have an account? <a href="javascript:void(0)" class="toggle-form"
                                            onclick="switchForm('register')">Register here</a></p>
                                </div>
                            @endif
                        </form>
                    </div>

                    <div class="form-box register">
                        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                            @csrf
                            <h2>Sign Up</h2>

                            <div class="input-box">
                                <span class="icon"><i class="bi bi-person"></i></span>
                                <input type="text" name="firstname" placeholder=" " value="{{ old('firstname') }}" required>
                                <label>First Name</label>
                                @error('firstname')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="bi bi-person"></i></span>
                                <input type="text" name="lastname" placeholder=" " value="{{ old('lastname') }}" required>
                                <label>Last Name</label>
                                @error('lastname')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="bi bi-person"></i></span>
                                <input type="text" name="middlename" placeholder=" " value="{{ old('middlename') }}">
                                <label>Middle Name (Optional)</label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" required>
                                <label>Email</label>
                                @error('email')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" required>
                                <label>Password</label>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password_confirmation" required>
                                <label>Confirm Password</label>
                                @error('password_confirmation')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn">Register</button>

                            <div class="login-link">
                                <p>Already have an account? <a href="javascript:void(0)" class="toggle-form"
                                        onclick="switchForm('login')">Sign in</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Terms and Conditions Modal -->
    <div class="modal modal-lg fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 0.95rem;">
                    By participating in this survey, you acknowledge and agree to the following:
                    <div class="ms-3">

                        <li>
                            Voluntary Participation: Your participation in this survey is entirely voluntary. You may choose to withdraw at any time without penalty.
                        </li>
                        <li>
                            Purpose of Data Collection: The information collected will be used solely for research purposes related to data privacy awareness among students.
                        </li>
                        <li>
                            Data Confidentiality: Your responses will be kept confidential and will only be accessed by authorized personnel involved in this study.
                        </li>
                        <li>
                            Anonymity: This survey is anonymous, and no personally identifiable information will be collected unless explicitly stated.
                        </li>
                        <li>
                            Data Storage and Security: Collected data will be stored securely and will be retained only for the duration necessary to complete the research.
                        </li>
                        <li>
                            Use of Data: The data may be published in aggregated form for academic or institutional reporting, without identifying individual participants.
                        </li>
                        <li>
                            Contact Information: For any questions regarding this survey or your rights, please contact graduatetracercpe@gmail.com
                        </li>
                    </div>
                    By clicking “I Agree” or submitting the survey, you consent to the terms stated above.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn text-white bg-secondary" data-bs-dismiss="modal" id="declineTerms">Disagree</button>
                    <button type="button" class="btn btn-primary" id="agreeTerms" data-bs-dismiss="modal">I Agree</button>
                </div>
            </div>
        </div>
    </div>


    <style>
        .auth-wrapper {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                linear-gradient(rgba(0, 0, 0, 0.5),
                    rgba(0, 0, 0, 0.5)),
                url('https://www.mmsu.edu.ph/storage/gallery/171020121128home-slidehu8xi/2203150159595.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(5px);
            z-index: -1;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            min-height: calc(100vh - 70px);
            padding-top: 90px;
        }

        .branding-section {
            flex: 1;
            padding: 2rem;
            color: white;
            margin-top: -2rem;
        }

        .form-container {
            flex: 1;
            max-width: 400px;
            margin-top: -2rem;
            position: relative;
            height: auto;
        }

        .forms-wrapper {
            position: relative;
            width: 100%;
            min-height: 400px;
        }

        .form-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 20px;
            position: absolute;
            width: 100%;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
            transform: translateX(50px);
            pointer-events: none;
            pointer-events: auto !important;
            opacity: 0;
            visibility: hidden;
            position: absolute;
            transition: all 0.3s ease;
        }

        .form-box.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
            position: relative;
            pointer-events: auto;
            opacity: 1;
            visibility: visible;
            position: relative;
            transform: translateX(0);
        }

        .input-box {
            position: relative;
            margin-bottom: 2rem;
        }

        .input-box input, .input-box select {
            width: 100%;
            padding: 10px 10px 10px 35px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            color: white;
            font-size: 15px;
            transition: all 0.3s;
        }

        .input-box input:focus, .input-box select:focus  {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.15);
        }

        .input-box label {
            position: absolute;
            left: 35px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            transition: all 0.3s;
            pointer-events: none;
        }

        .input-box input:focus~label,
        .input-box input:not(:placeholder-shown)~label,  .input-box select:not(:placeholder-shown)~label{
            top: -8px;
            left: 12px;
            font-size: 12px;
            padding: 0 5px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 3px;
            color: white;
        }

        .input-box .icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            z-index: 1;
        }

        .error-message {
            position: absolute;
            bottom: -20px;
            left: 10px;
            color: #ff6b6b;
            font-size: 12px;
            animation: fadeInUp 0.3s ease;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            color: white;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 1rem;
        }

        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: none;
            box-shadow: none;
        }

        .register-link,
        .login-link {
            margin-top: 1.5rem;
            text-align: center;
        }

        .register-link p,
        .login-link p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .mmsu-logo {
            width: 100px;
            height: auto;
            margin-right: 1rem;
        }

        .mmsu-text {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .welcome-text h2 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }

        .welcome-text span {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.8rem;
        }

        .welcome-text p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 1rem;
                padding-top: 80px;
                justify-content: flex-start;
                min-height: calc(100vh - 60px);
            }

            .branding-section {
                text-align: center;
                margin-bottom: 2rem;
                margin-top: 0;
                padding: 1rem;
            }

            .form-container {
                width: 100%;
                margin-top: 0;
                padding: 0 1rem;
            }

            .forms-wrapper {
                min-height: 450px;
            }

            .mmsu-logo {
                width: 80px;
                margin: auto;
            }

            .mmsu-text {
                font-size: 1.1rem;
            }

            .welcome-text h2 {
                font-size: 1.8rem;
            }

            .welcome-text span {
                font-size: 1.4rem;
            }

            .container {
                padding-top: 56px;
            }
        }

        @media (max-height: 700px) {
            .container {
                padding-top: 4rem;
            }

            .form-box {
                padding: 1.5rem;
            }

            .input-box {
                margin-bottom: 1.5rem;
            }
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: rgba(255, 107, 107, 0.9);
            color: white;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(-10px);
            transition: 0.3s ease;
            z-index: 1100;
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Fix form switching styles */
        .toggle-form {
            cursor: pointer !important;
            color: #fff !important;
            text-decoration: underline !important;
            transition: all 0.3s ease;
            display: inline-block;
            z-index: 9999;
            position: relative;
        }

        .form-box {
            position: absolute;
            width: 100%;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-box.active {
            position: relative;
            opacity: 1;
            visibility: visible;
            pointer-events: all;
            transition: all 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle form switching
            window.switchForm = function (formType) {
                const forms = document.querySelectorAll('.form-box');
                forms.forEach(form => {
                    form.classList.remove('active');
                    form.style.pointerEvents = 'none'; // Disable interaction while switching
                });
                const activeForm = document.querySelector(`.form-box.${formType}`);
                activeForm.classList.add('active');
                activeForm.style.pointerEvents = 'auto'; // Re-enable interaction

                // Update URL without refreshing
                history.pushState(null, '', formType === 'register' ? '#register' : '#login');
            }

            // Add click handlers directly to links
            document.querySelectorAll('.toggle-form').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const formType = link.getAttribute('onclick').includes('register') ? 'register' : 'login';
                    switchForm(formType);
                });
            });

            // Check hash on page load
            if (window.location.hash === '#register') {
                switchForm('register');
            }

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function () {
                const formType = window.location.hash === '#register' ? 'register' : 'login';
                switchForm(formType);
            });

            // Add toast notification for errors
            @if($errors->any())
                const errorMessages = @json($errors->all());
                errorMessages.forEach(message => {
                    const toast = document.createElement('div');
                    toast.className = 'toast-notification error';
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.classList.add('show');
                        setTimeout(() => {
                            toast.classList.remove('show');
                            setTimeout(() => toast.remove(), 300);
                        }, 3000);
                    }, 100);
                });
            @endif

            // Form validation and error handling
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const inputs = form.querySelectorAll('input');
                inputs.forEach(input => {
                    input.addEventListener('input', function () {
                        this.setAttribute('value', this.value);
                    });
                });

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (form.checkValidity()) {
                        // Add loading state to button
                        const btn = form.querySelector('button[type="submit"]');
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                        btn.disabled = true;

                        // Submit form
                        form.submit();
                    } else {
                        showToast('Please fill all required fields correctly');
                    }
                });
            });

            function showToast(message) {
                const toast = document.createElement('div');
                toast.className = 'toast-notification error';
                toast.textContent = message;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('show');
                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }, 100);
            }
        });

        const signinBtn = document.getElementById('signinBtn');
        const termsCheckbox = document.getElementById('termsTriggerCheck');
        const agreeBtn = document.getElementById('agreeTerms');
        const declineBtn = document.getElementById('declineTerms');

        // Initially keep button disabled
        signinBtn.disabled = true;
        termsCheckbox.checked = false;

        // When modal is shown by checking the box, keep it unchecked until agreed
        termsCheckbox.addEventListener('change', function () {
            if (this.checked) {
                this.checked = false; // force user to agree through modal
            }
        });

        // Handle agreement
        agreeBtn.addEventListener('click', function () {
            termsCheckbox.checked = true;
            signinBtn.disabled = false;
        });

        // Optional: keep button disabled if user declines
        declineBtn.addEventListener('click', function () {
            termsCheckbox.checked = false;
            signinBtn.disabled = true;
        });

    </script>
@endsection