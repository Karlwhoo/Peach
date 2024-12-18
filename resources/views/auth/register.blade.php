<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Peach Perfect') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <!-- App CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --peach-primary: #FF8B5E;
            --peach-secondary: #FFA07A;
            --peach-light: #FFE4E1;
            --peach-dark: #E68A45;
            --peach-text: #C0615B;
            --white: #FFFFFF;
            --gray-100: #F8F9FA;
            --gray-200: #E9ECEF;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--gray-100);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .left {
            background: linear-gradient(135deg, var(--peach-primary) 0%, var(--peach-secondary) 100%);
            height: 100vh;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('/img/pattern.png');
            opacity: 0.1;
            animation: pulse 8s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.05; }
            50% { opacity: 0.15; }
            100% { opacity: 0.05; }
        }

        .left-heading {
            position: relative;
            z-index: 1;
        }

        .left-heading h1 {
            color: var(--white);
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            animation: slideIn 1s ease-out;
        }

        .left-heading h4 {
            color: var(--white);
            font-size: 1.8rem;
            font-weight: 500;
            margin-bottom: 1rem;
            opacity: 0;
            animation: fadeIn 1s ease-out 0.5s forwards;
        }

        .left-heading p {
            color: var(--white);
            font-size: 1.1rem;
            opacity: 0;
            animation: fadeIn 1s ease-out 1s forwards;
        }

        @keyframes slideIn {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .right {
            background-color: var(--white);
            height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem;
        }

        .auth-form {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            background-color: var(--white);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        .auth-form:hover {
            transform: translateY(-5px);
        }

        .form-label {
            color: var(--peach-text);
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--peach-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 139, 94, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--peach-primary) 0%, var(--peach-secondary) 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--peach-secondary) 0%, var(--peach-primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 139, 94, 0.2);
        }

        .form-check-input:checked {
            background-color: var(--peach-primary);
            border-color: var(--peach-primary);
        }

        a {
            color: var(--peach-text);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        a:hover {
            color: var(--peach-dark);
        }

        .auth-form h3 {
            color: var(--peach-text);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 767.98px) {
            .left {
                height: 40vh;
                min-height: auto;
                padding: 2rem;
            }
            
            .right {
                height: auto;
                min-height: 60vh;
                padding: 2rem 1rem;
            }
            
            .auth-form {
                padding: 1.5rem;
            }
            
            .left-heading h1 {
                font-size: 2.5rem;
            }

            .left-heading h4 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="row g-0">
        <div class="col-md-4">
            <div class="left">
                <div class="left-heading text-center">
                    <h1>The Apple Peach House</h1>
                    <h4>Staff Registration</h4>
                    <p>Please contact your administrator for account access</p>
                    <div class="contact-info mt-4">
                        <small class="text-white-50">
                            <i class="bi bi-envelope-fill me-2"></i>admin@applepeachhouse.com
                        </small>
                        <br>
                        <small class="text-white-50">
                            <i class="bi bi-telephone-fill me-2"></i>+63 123 456 7890
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="right">
                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf
                    <h3 class="text-center">Create your account</h3>
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name') }}" 
                            placeholder="Enter your full name"
                            required 
                            autofocus
                        >
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email"
                            required
                        >
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-control @error('password') is-invalid @enderror" 
                            placeholder="Create a password"
                            required
                        >
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="form-control" 
                            placeholder="Confirm your password"
                            required
                        >
                    </div>

                    <div class="form-check mb-4">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="terms" 
                            name="terms" 
                            required
                        >
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>

                    <div class="text-center">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
