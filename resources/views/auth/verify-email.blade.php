<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - E-Prescription System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .verification-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .verification-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .btn-verify {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-resend {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-resend:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .verification-steps {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .step-item:hover {
            background: rgba(102, 126, 234, 0.1);
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }
        
        @media (max-width: 768px) {
            .verification-container {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            
            .verification-icon {
                width: 60px;
                height: 60px;
            }
        }
        
        /* Security features */
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        .verification-container {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="verification-container p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="verification-icon">
                            <i class="fas fa-envelope-open-text fa-2x text-white"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-2">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            Verifikasi Email
                        </h2>
                        <p class="text-muted">
                            Sebelum melanjutkan, silakan verifikasi alamat email Anda
                        </p>
                    </div>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Verification Steps -->
                    <div class="verification-steps">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-list-ol text-primary me-2"></i>
                            Langkah Verifikasi:
                        </h6>
                        
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div>
                                <strong>Cek Email Anda</strong>
                                <div class="text-muted small">Buka email yang terdaftar</div>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div>
                                <strong>Cari Email Verifikasi</strong>
                                <div class="text-muted small">Cari email dari E-Prescription System</div>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div>
                                <strong>Klik Link Verifikasi</strong>
                                <div class="text-muted small">Klik tombol "Verifikasi Email" dalam email</div>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">4</div>
                            <div>
                                <strong>Login ke Sistem</strong>
                                <div class="text-muted small">Setelah verifikasi, Anda dapat login</div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Info -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg me-3 text-primary"></i>
                            <div>
                                <strong>Email Terdaftar:</strong><br>
                                <span class="text-primary fw-bold">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-3">
                        <!-- Resend Verification Email -->
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-resend w-100">
                                <i class="fas fa-paper-plane me-2"></i>
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>
                        
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>

                    <!-- Help Section -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="text-center">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fas fa-question-circle text-primary me-2"></i>
                                Butuh Bantuan?
                            </h6>
                            <p class="text-muted small mb-0">
                                Jika Anda tidak menerima email verifikasi, cek folder spam atau 
                                <a href="mailto:support@eprescription.com" class="text-decoration-none">
                                    hubungi support
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Security: Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        
        // Security: Disable F12, Ctrl+Shift+I, Ctrl+U
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Add loading state to resend button
        document.querySelector('form[action*="verification.send"]').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
            button.disabled = true;
            
            setTimeout(function() {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });
    </script>
</body>
</html> 