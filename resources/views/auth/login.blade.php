<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Rental Mobil WakPim</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons & AdminLTE -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style>
        :root {
            --primary-color: #1e3a8a;
            --primary-light: #3b82f6;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
            --dark-color: #0f172a;
        }

       body.login-page {
        background: 
            linear-gradient(rgba(15, 23, 42, 0.9), rgba(30, 58, 138, 0.9)),
            url("{{ asset('assets/login-bg.jpg') }}");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .login-box {
            width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container {
            display: inline-block;
            position: relative;
            margin-bottom: 20px;
        }

        .logo-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 35px rgba(30, 58, 138, 0.4);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .logo-circle i {
            font-size: 3rem;
            color: white;
        }

        .brand-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .brand-subtitle {
            color: #cbd5e1;
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Card Styling */
        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .login-card-body {
            padding: 40px;
        }

        .login-box-msg {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .login-box-msg::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            border-radius: 2px;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 25px;
        }

        .input-label {
            display: block;
            margin-bottom: 8px;
            color: #4b5563;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .input-group {
            position: relative;
        }

        .form-control {
            height: 52px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            padding: 0 45px 0 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f9fafb;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
        }

        /* Remember Me */
        .remember-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .icheck-primary input {
            width: 18px;
            height: 18px;
        }

        .icheck-primary label {
            margin-left: 8px;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .forgot-link {
            color: var(--primary-light);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .forgot-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 58, 138, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn i {
            margin-right: 10px;
        }

        /* Demo Credentials */
        .demo-credentials {
            margin-top: 25px;
            padding: 15px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            border-left: 4px solid var(--primary-light);
        }

        .demo-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .demo-item {
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: #4b5563;
        }

        .demo-role {
            display: inline-block;
            padding: 2px 8px;
            background: var(--primary-light);
            color: white;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 5px;
        }

        /* Footer */
        .login-footer {
            margin-top: 30px;
            text-align: center;
            color: #cbd5e1;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: #e0f2fe;
            text-decoration: none;
        }

        .login-footer a:hover {
            color: white;
            text-decoration: underline;
        }

        /* Error Messages */
        .invalid-feedback {
            display: block;
            margin-top: 5px;
            font-size: 0.85rem;
            color: #ef4444;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 25px;
        }

        /* Success Animation */
        .success-animation {
            animation: successSlide 0.5s ease-out;
        }

        @keyframes successSlide {
            0% { transform: translateY(-20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-box {
                width: 90%;
            }
            
            .login-card-body {
                padding: 30px 20px;
            }
            
            .logo-circle {
                width: 80px;
                height: 80px;
            }
            
            .logo-circle i {
                font-size: 2.5rem;
            }
        }

        /* Loading Animation */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
        }

        @keyframes button-loading-spinner {
            from { transform: rotate(0turn); }
            to { transform: rotate(1turn); }
        }
    </style>
</head>

<body class="hold-transition login-page">

<div class="login-box">

    <!-- Logo & Brand -->
    <div class="login-logo">
        <div class="logo-container">
            <div class="logo-circle">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="height: 60px;">
            </div>
        </div>
        <h1 class="brand-title">RENTAL WAKPIM</h1>
        <p class="brand-subtitle">Sistem Pengelolaan Mobil</p>
    </div>

    <!-- Login Card -->
    <div class="card">
        <div class="card-body login-card-body">

            <p class="login-box-msg">
                <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Sistem
            </p>

            <!-- Status Messages -->
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show success-animation">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h6><i class="icon fas fa-check-circle mr-2"></i>Berhasil!</h6>
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show success-animation">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h6><i class="icon fas fa-exclamation-circle mr-2"></i>Error!</h6>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label class="input-label">Email</label>
                    <div class="input-group">
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Masukkan email Anda"
                               value="{{ old('email') }}"
                               required 
                               autofocus>
                        <span class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="input-label">Password</label>
                    <div class="input-group">
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Masukkan password Anda"
                               required>
                        <span class="input-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="remember-container">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Ingat Saya</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            <i class="fas fa-key mr-1"></i>Lupa Password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div class="form-group">
                    <button type="submit" class="login-btn" id="loginBtn">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Footer -->
    <div class="login-footer">
        <p>
            &copy; {{ date('Y') }} <a href="#">Rental Mobil WakPim</a>. 
            Hak Cipta Dilindungi.
            <br>
            <small>Version 1.0.0</small>
        </p>
    </div>

</div>

<!-- JavaScript -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

<script>
    // Form submission loading
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('loginBtn');
        btn.classList.add('btn-loading');
        btn.innerHTML = 'Memproses...';
        btn.disabled = true;
    });

    // Auto focus on email field if error
    @if($errors->has('email') || $errors->has('password'))
        setTimeout(() => {
            document.querySelector('input[name="email"]').focus();
        }, 100);
    @endif

    // Password visibility toggle (optional enhancement)
    // Uncomment if you want to add show/hide password
    /*
    const passwordInput = document.querySelector('input[name="password"]');
    const passwordIcon = document.querySelector('.input-icon .fa-lock');
    passwordIcon.style.cursor = 'pointer';
    
    passwordIcon.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-lock');
    });
    */

    // Smooth error display
    @error('email')
        setTimeout(() => {
            document.querySelector('input[name="email"]').classList.add('is-invalid');
        }, 100);
    @enderror

    @error('password')
        setTimeout(() => {
            document.querySelector('input[name="password"]').classList.add('is-invalid');
        }, 100);
    @enderror

    // Input focus effects
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.querySelector('.input-icon').style.color = 'var(--primary-light)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.querySelector('.input-icon').style.color = '#9ca3af';
        });
    });

    // Auto dismiss alerts after 5 seconds
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>

</body>
</html>