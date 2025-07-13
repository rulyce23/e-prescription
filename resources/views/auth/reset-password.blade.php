<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - E-Prescription System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            position: relative;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .form-control.is-valid {
            border-color: var(--success-color);
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 2;
            cursor: pointer;
        }

        .input-with-icon {
            padding-left: 50px;
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 2;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .btn {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6c3 100%);
            color: #155724;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid var(--danger-color);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 14px;
            margin-top: 8px;
            font-weight: 500;
        }

        .valid-feedback {
            display: block;
            color: var(--success-color);
            font-size: 14px;
            margin-top: 8px;
            font-weight: 500;
        }

        .password-strength {
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
        }

        .password-strength.weak {
            background: #f8d7da;
            color: #721c24;
            border-left: 3px solid var(--danger-color);
        }

        .password-strength.medium {
            background: #fff3cd;
            color: #856404;
            border-left: 3px solid var(--warning-color);
        }

        .password-strength.strong {
            background: #d4edda;
            color: #155724;
            border-left: 3px solid var(--success-color);
        }

        .password-requirements {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--info-color);
        }

        .password-requirements h6 {
            color: var(--info-color);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #2c3e50;
        }

        .requirement-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }

        .requirement-item.valid i {
            color: var(--success-color);
        }

        .requirement-item.invalid i {
            color: var(--danger-color);
        }

        .login-footer {
            text-align: center;
            padding: 20px 30px;
            background: var(--light-color);
            border-top: 1px solid #e9ecef;
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: var(--secondary-color);
        }

        .loading {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header, .login-body, .login-footer {
                padding: 30px 20px;
            }
            
            .login-header h1 {
                font-size: 24px;
            }
            
            .form-control {
                padding: 12px 15px;
                font-size: 16px;
            }
        }

        /* Animation for form elements */
        .form-group {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-key"></i> Reset Password</h1>
            <p>Masukkan password baru untuk akun Anda</p>
        </div>

        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="password-requirements">
                <h6><i class="fas fa-shield-alt"></i> Persyaratan Password</h6>
                <div class="requirement-item" id="req-length">
                    <i class="fas fa-circle"></i> Minimal 8 karakter
                </div>
                <div class="requirement-item" id="req-uppercase">
                    <i class="fas fa-circle"></i> Mengandung huruf besar
                </div>
                <div class="requirement-item" id="req-lowercase">
                    <i class="fas fa-circle"></i> Mengandung huruf kecil
                </div>
                <div class="requirement-item" id="req-number">
                    <i class="fas fa-circle"></i> Mengandung angka
                </div>
                <div class="requirement-item" id="req-symbol">
                    <i class="fas fa-circle"></i> Mengandung simbol
                </div>
            </div>

            <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Password Baru
                    </label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               class="form-control input-with-icon @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password baru"
                               required 
                               autocomplete="new-password"
                               autofocus>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                    <div class="password-strength" id="passwordStrength" style="display: none;"></div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock"></i> Konfirmasi Password
                    </label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               class="form-control input-with-icon" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Konfirmasi password baru"
                               required 
                               autocomplete="new-password">
                        <i class="fas fa-eye password-toggle" id="togglePasswordConfirm"></i>
                    </div>
                    <div class="valid-feedback" id="passwordMatch" style="display: none;">
                        <i class="fas fa-check-circle"></i> Password cocok
                    </div>
                    <div class="invalid-feedback" id="passwordMismatch" style="display: none;">
                        <i class="fas fa-exclamation-circle"></i> Password tidak cocok
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">
                            <i class="fas fa-save"></i> Reset Password
                        </span>
                        <span class="loading">
                            <div class="spinner"></div>
                        </span>
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                </a>
            </div>
        </div>

        <div class="login-footer">
            <p>Ingat password Anda? <a href="{{ route('login') }}">Login di sini</a></p>
            <p><small>&copy; {{ date('Y') }} E-Prescription System. All rights reserved.</small></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const togglePassword = document.getElementById('togglePassword');
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordMatch = document.getElementById('passwordMatch');
            const passwordMismatch = document.getElementById('passwordMismatch');
            const form = document.getElementById('resetPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const loading = submitBtn.querySelector('.loading');

            // Password visibility toggle
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });

            togglePasswordConfirm.addEventListener('click', function() {
                const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /\d/.test(password),
                    symbol: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                };

                // Update requirement indicators
                Object.keys(requirements).forEach(req => {
                    const element = document.getElementById('req-' + req);
                    if (requirements[req]) {
                        element.classList.add('valid');
                        element.classList.remove('invalid');
                        element.querySelector('i').className = 'fas fa-check';
                        strength++;
                    } else {
                        element.classList.add('invalid');
                        element.classList.remove('valid');
                        element.querySelector('i').className = 'fas fa-times';
                    }
                });

                // Show strength indicator
                passwordStrength.style.display = 'block';
                if (strength < 3) {
                    passwordStrength.className = 'password-strength weak';
                    passwordStrength.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Password lemah';
                } else if (strength < 5) {
                    passwordStrength.className = 'password-strength medium';
                    passwordStrength.innerHTML = '<i class="fas fa-info-circle"></i> Password sedang';
                } else {
                    passwordStrength.className = 'password-strength strong';
                    passwordStrength.innerHTML = '<i class="fas fa-check-circle"></i> Password kuat';
                }

                return strength;
            }

            // Password confirmation checker
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;

                if (confirm === '') {
                    passwordMatch.style.display = 'none';
                    passwordMismatch.style.display = 'none';
                    return;
                }

                if (password === confirm) {
                    passwordMatch.style.display = 'block';
                    passwordMismatch.style.display = 'none';
                    confirmInput.classList.add('is-valid');
                    confirmInput.classList.remove('is-invalid');
                } else {
                    passwordMatch.style.display = 'none';
                    passwordMismatch.style.display = 'block';
                    confirmInput.classList.add('is-invalid');
                    confirmInput.classList.remove('is-valid');
                }
            }

            // Event listeners
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });

            confirmInput.addEventListener('input', function() {
                checkPasswordMatch();
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return false;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                loading.style.display = 'block';
                
                // Submit form
                form.submit();
            });

            // Form validation
            function validateForm() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;
                
                // Reset validation states
                passwordInput.classList.remove('is-invalid');
                confirmInput.classList.remove('is-invalid');
                
                let isValid = true;
                
                // Password validation
                if (!password) {
                    passwordInput.classList.add('is-invalid');
                    showError('Password wajib diisi');
                    isValid = false;
                } else if (checkPasswordStrength(password) < 3) {
                    passwordInput.classList.add('is-invalid');
                    showError('Password tidak memenuhi persyaratan keamanan');
                    isValid = false;
                }
                
                // Confirmation validation
                if (!confirm) {
                    confirmInput.classList.add('is-invalid');
                    showError('Konfirmasi password wajib diisi');
                    isValid = false;
                } else if (password !== confirm) {
                    confirmInput.classList.add('is-invalid');
                    showError('Konfirmasi password tidak cocok');
                    isValid = false;
                }
                
                return isValid;
            }
            
            // Show error message
            function showError(message) {
                // Remove existing alerts
                const existingAlert = document.querySelector('.alert-danger');
                if (existingAlert) {
                    existingAlert.remove();
                }
                
                // Create new alert
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                alert.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
                
                // Insert after header
                const loginBody = document.querySelector('.login-body');
                loginBody.insertBefore(alert, loginBody.firstChild);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 5000);
            }
        });
        
        // Prevent form resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Security measures
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.parentNode) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }
            });
        }, 5000);
    </script>
</body>
</html> 