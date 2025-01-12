<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Password Recovery</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --peach-primary: #FF8B5E;
            --peach-secondary: #FFA07A;
            --peach-light: #FFE4E1;
            --peach-dark: #E68A45;
            --peach-text: #C0615B;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--peach-primary) 0%, var(--peach-secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .brand-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 3px solid white;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--peach-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 139, 94, 0.25);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            background-color: #f8f9fa;
            border: 2px solid #eee;
            border-right: none;
        }

        .btn-primary {
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            background: linear-gradient(45deg, var(--peach-primary), var(--peach-secondary));
            border: none;
            box-shadow: 0 4px 15px rgba(255, 139, 94, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 139, 94, 0.4);
            background: linear-gradient(45deg, var(--peach-secondary), var(--peach-primary));
        }

        .otp-input {
            width: 45px !important;
            height: 45px;
            font-size: 1.2rem;
            text-align: center;
            border-radius: 8px;
        }

        .otp-input:focus {
            border-color: var(--peach-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 139, 94, 0.25);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .brand-text-container {
            text-align: center;
            margin-top: 10px;
        }

        .brand-text-main {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--peach-text);
            display: block;
            margin-bottom: 5px;
        }

        .brand-text-sub {
            font-size: 0.9rem;
            color: var(--peach-secondary);
            opacity: 0.8;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .step {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #eee;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .step.active {
            background: var(--peach-primary);
            transform: scale(1.2);
        }

        .info-message {
            background-color: #17a2b8;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .otp-container, .password-container {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .otp-container.active, .password-container.active {
            display: block;
            opacity: 1;
        }

        .back-to-login {
            color: var(--peach-text);
            transition: all 0.3s ease;
        }

        .back-to-login:hover {
            color: var(--peach-primary);
            text-decoration: none;
        }

        /* Loading Animation Styles */
        .spinner-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 139, 94, 0.05);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }

        .spinner-overlay.active {
            display: flex;
        }

        .spinner {
            width: 60px;
            height: 60px;
            position: relative;
            margin-bottom: 20px;
        }

        .spinner::before,
        .spinner::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            animation: pulse 1.8s ease-in-out infinite;
            box-shadow: 0 0 20px rgba(255, 139, 94, 0.2);
        }

        .spinner::before {
            width: 100%;
            height: 100%;
            background: rgba(255, 139, 94, 0.2);
            animation-delay: -0.5s;
        }

        .spinner::after {
            width: 75%;
            height: 75%;
            background: var(--peach-primary);
            top: 12.5%;
            left: 12.5%;
            animation-delay: 0s;
            filter: drop-shadow(0 0 8px rgba(255, 139, 94, 0.5));
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(0.5);
                opacity: 0.8;
            }
            50% {
                transform: scale(1);
                opacity: 0.5;
            }
        }

        .loading-text {
            color: var(--peach-text);
            font-size: 1rem;
            font-weight: 500;
            text-align: center;
            margin-top: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .loading-progress {
            width: 180px;
            height: 2px;
            background: rgba(255, 139, 94, 0.1);
            border-radius: 4px;
            margin-top: 15px;
            overflow: hidden;
            position: relative;
        }

        .loading-progress::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 40%;
            background: var(--peach-primary);
            border-radius: 4px;
            animation: progress 1.2s ease-in-out infinite;
            box-shadow: 0 0 10px rgba(255, 139, 94, 0.3);
        }

        @keyframes progress {
            0% {
                left: -40%;
            }
            100% {
                left: 100%;
            }
        }

        .step-content {
            position: relative;
            min-height: 300px;
        }

        .fade-transition {
            transition: opacity 0.3s ease-in-out;
        }

        .fade-out {
            opacity: 0;
        }

        .fade-in {
            opacity: 1;
        }

        .step-section {
            display: none;
        }

        .step-section.active {
            display: block;
        }

        .swal2-popup {
            font-family: 'Poppins', sans-serif;
        }

        .swal2-title {
            color: var(--peach-text) !important;
        }

        .swal2-confirm {
            background: linear-gradient(45deg, var(--peach-primary), var(--peach-secondary)) !important;
            border: none !important;
            box-shadow: 0 4px 15px rgba(255, 139, 94, 0.3) !important;
        }

        .swal2-confirm:hover {
            background: linear-gradient(45deg, var(--peach-secondary), var(--peach-primary)) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 139, 94, 0.4) !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0">
                    <div class="card-body p-5">
                        <!-- Logo and Title -->
                        <div class="text-center mb-4">
                            <img src="/uploads/peach.jfif" alt="Logo" class="brand-image mb-3">
                            <div class="brand-text-container">
                                <span class="brand-text-main">The Apple Peach House</span>
                                <span class="brand-text-sub">Password Recovery</span>
                            </div>
                        </div>

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step active" data-step="1"></div>
                            <div class="step" data-step="2"></div>
                            <div class="step" data-step="3"></div>
                        </div>

                        <div class="step-content">
                            <!-- Loading Overlay -->
                            <div class="spinner-overlay">
                                <div class="spinner"></div>
                                <div class="loading-text">Processing your request...</div>
                                <div class="loading-progress"></div>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success text-center mb-4">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('status') }}
                                </div>
                            @endif

                            <!-- Validation Errors -->
                            <div id="errorContainer" class="alert alert-danger mb-4" style="display: none;">
                                <ul class="mb-0 list-unstyled">
                                </ul>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0 list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Email Form Step -->
                            <div id="emailStep" class="step-section active">
                                <form id="emailForm" class="mb-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" 
                                                class="form-control" 
                                                id="email" 
                                                name="email" 
                                                required
                                                placeholder="Enter your email">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Send Recovery Code
                                    </button>
                                </form>
                            </div>

                            <!-- OTP Verification Step -->
                            <div id="otpStep" class="step-section" style="display: none;">
                                <h5 class="text-center mb-4">Enter Verification Code</h5>
                                <p class="text-center text-muted mb-4">Please enter the verification code sent to your email</p>
                                
                                <form id="otpForm" class="mb-4">
                                    <div class="otp-inputs d-flex justify-content-center gap-2 mb-4">
                                        <input type="text" class="form-control otp-input text-center" maxlength="1" pattern="[0-9]" required>
                                        <input type="text" class="form-control otp-input text-center" maxlength="1" pattern="[0-9]" required>
                                        <input type="text" class="form-control otp-input text-center" maxlength="1" pattern="[0-9]" required>
                                        <input type="text" class="form-control otp-input text-center" maxlength="1" pattern="[0-9]" required>
                                        <input type="text" class="form-control otp-input text-center" maxlength="1" pattern="[0-9]" required>
                                        <input type="text" class="form-control otp-input text-center" maxlength="1" pattern="[0-9]" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mb-3">Verify Code</button>
                                    <div class="text-center">
                                        <button type="button" id="resendOtp" class="btn btn-link">Didn't receive the code? Resend</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Password Reset Step -->
                            <div id="passwordStep" class="step-section" style="display: none;">
                                <h5 class="text-center mb-4">Reset Your Password</h5>
                                <form id="passwordForm">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="back-to-login">
                                <i class="fas fa-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailForm = document.getElementById('emailForm');
            const otpStep = document.getElementById('otpStep');
            const emailStep = document.getElementById('emailStep');
            const passwordStep = document.getElementById('passwordStep');
            
            function showStep(stepToShow) {
                // Hide all steps
                [emailStep, otpStep, passwordStep].forEach(step => {
                    step.style.display = 'none';
                    step.classList.remove('active');
                });
                
                // Show the target step
                stepToShow.style.display = 'block';
                stepToShow.classList.add('active');
            }
            
            emailForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.querySelector('input[name="email"]').value;
                
                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
                submitButton.disabled = true;

                fetch('{{ route("password.send-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show OTP step
                        showStep(otpStep);
                        
                        // Update step indicator
                        document.querySelectorAll('.step')[0].classList.remove('active');
                        document.querySelectorAll('.step')[1].classList.add('active');
                        
                        // Focus first OTP input
                        document.querySelector('.otp-input').focus();
                    } else {
                        alert(data.message || 'Failed to send recovery code');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to send recovery code');
                })
                .finally(() => {
                    // Reset button state
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                });
            });

            // Handle OTP input
            const otpInputs = document.querySelectorAll('.otp-input');
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    if (this.value.length === 1) {
                        if (index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            // Handle OTP form submission
            const otpForm = document.getElementById('otpForm');
            otpForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const otpValue = Array.from(otpInputs).map(input => input.value).join('');
                const email = document.querySelector('input[name="email"]').value;

                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';
                submitButton.disabled = true;

                fetch('{{ route("password.verify-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        otp: otpValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show password reset form
                        showStep(passwordStep);
                        
                        // Update step indicator
                        document.querySelectorAll('.step')[1].classList.remove('active');
                        document.querySelectorAll('.step')[2].classList.add('active');
                    } else {
                        alert(data.message || 'Invalid OTP');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to verify OTP');
                })
                .finally(() => {
                    // Reset button state
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                });
            });

            // Handle resend OTP
            document.getElementById('resendOtp').addEventListener('click', function(e) {
                e.preventDefault();
                
                const email = document.querySelector('input[name="email"]').value;
                
                fetch('{{ route("password.send-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Recovery code resent successfully!');
                    } else {
                        alert(data.message || 'Failed to resend recovery code');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to resend recovery code');
                });
            });

            // Add this to your existing JavaScript
            const passwordForm = document.getElementById('passwordForm');
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.querySelector('input[name="email"]').value;
                const password = document.getElementById('password').value;
                const password_confirmation = document.getElementById('password_confirmation').value;

                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Resetting...';
                submitButton.disabled = true;

                fetch('{{ route("password.reset") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message with SweetAlert or custom modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Your password has been reset successfully.',
                            confirmButtonColor: '#FF8B5E',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("login") }}';
                            }
                        });
                    } else {
                        alert(data.message || 'Failed to reset password');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to reset password');
                })
                .finally(() => {
                    // Reset button state
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                });
            });
        });
    </script>
</body>
</html>
