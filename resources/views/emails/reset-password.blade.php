<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - E-Prescription System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .message {
            font-size: 16px;
            margin-bottom: 30px;
            color: #555;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .warning strong {
            color: #d63031;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 12px;
            color: #6c757d;
        }
        .security-info {
            background-color: #e8f5e8;
            border: 1px solid #c3e6c3;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #155724;
        }
        .security-info strong {
            color: #28a745;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .header h1 {
                font-size: 20px;
            }
            .greeting {
                font-size: 16px;
            }
            .message {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
            <p>E-Prescription System</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $user->name }}</strong>,
            </div>
            
            <div class="message">
                Kami menerima permintaan untuk mereset password akun Anda di E-Prescription System. 
                Jika Anda tidak melakukan permintaan ini, Anda dapat mengabaikan email ini.
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/reset-password/' . $token . '?email=' . urlencode($user->email)) }}" 
                   class="reset-button">
                    🔑 Reset Password Saya
                </a>
            </div>
            
            <div class="warning">
                <strong>⚠️ Peringatan Keamanan:</strong><br>
                • Link ini hanya berlaku selama <strong>60 menit</strong><br>
                • Link hanya bisa digunakan <strong>satu kali</strong><br>
                • Jangan bagikan link ini kepada siapapun<br>
                • Jika Anda tidak meminta reset password, abaikan email ini
            </div>
            
            <div class="security-info">
                <strong>🔒 Informasi Keamanan:</strong><br>
                • Permintaan ini dibuat dari IP: <strong>{{ request()->ip() }}</strong><br>
                • Waktu permintaan: <strong>{{ now()->format('d/m/Y H:i:s') }}</strong><br>
                • Browser: <strong>{{ request()->userAgent() }}</strong>
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                <strong>📧 Jika tombol tidak berfungsi:</strong><br>
                Salin dan tempel link berikut ke browser Anda:<br>
                <a href="{{ url('/reset-password/' . $token . '?email=' . urlencode($user->email)) }}" 
                   style="color: #667eea; word-break: break-all;">
                    {{ url('/reset-password/' . $token . '?email=' . urlencode($user->email)) }}
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>E-Prescription System</strong></p>
            <p>Digital Prescription Management</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini</p>
            <p>© {{ date('Y') }} E-Prescription System. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 