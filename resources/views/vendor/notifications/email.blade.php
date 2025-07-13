<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'E-Prescription System' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .email-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .alert-info {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            color: #1976d2;
        }
        
        .alert-warning {
            background-color: #fff3e0;
            border: 1px solid #ffcc02;
            color: #f57c00;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-muted {
            color: #6c757d;
        }
        
        .divider {
            border-top: 1px solid #e9ecef;
            margin: 30px 0;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 5px;
            }
            
            .email-header,
            .email-body,
            .email-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>
                <i class="fas fa-shield-alt"></i>
                E-Prescription System
            </h1>
            <p>Sistem Resep Digital Terpercaya</p>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            @if(isset($greeting))
                <h2>{{ $greeting }}</h2>
            @endif
            
            @foreach($introLines as $line)
                <p>{{ $line }}</p>
            @endforeach
            
            @if(isset($actionText))
                <div class="text-center">
                    <a href="{{ $actionUrl }}" class="btn">
                        {{ $actionText }}
                    </a>
                </div>
            @endif
            
            @foreach($outroLines as $line)
                <p>{{ $line }}</p>
            @endforeach
            
            @if(isset($actionText))
                <div class="alert alert-info">
                    <strong>Penting:</strong> Jika tombol di atas tidak berfungsi, 
                    salin dan tempel link berikut ke browser Anda:
                    <br><br>
                    <a href="{{ $actionUrl }}" style="word-break: break-all; color: #1976d2;">
                        {{ $actionUrl }}
                    </a>
                </div>
            @endif
            
            <div class="divider"></div>
            
            <div class="alert alert-warning">
                <strong>Keamanan:</strong> Link ini akan kadaluarsa dalam 60 menit. 
                Jangan bagikan link ini kepada siapapun.
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <p class="text-muted">
                <strong>E-Prescription System</strong><br>
                Sistem Resep Digital Terpercaya
            </p>
            <p class="text-muted" style="font-size: 12px;">
                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.<br>
                Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
            </p>
        </div>
    </div>
</body>
</html> 