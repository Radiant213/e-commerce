<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Kata Sandi</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0f172a; margin: 0; font-size: 24px; }
        .content { margin-bottom: 30px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #0f172a; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0; text-align: center; }
        .footer { text-align: center; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .url-text { word-break: break-all; font-size: 12px; color: #64748b; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Kata Sandi Anda</h1>
        </div>
        
        <div class="content">
            <p>Halo,</p>
            <p>Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda di {{ config('app.name') }}.</p>
            
            <div style="text-align: center;">
                @php
                    $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
                    $resetLink = $frontendUrl . '/reset-password?token=' . $token . '&email=' . urlencode($email);
                @endphp
                <a href="{{ $resetLink }}" class="button">Reset Kata Sandi</a>
            </div>
            
            <p>Tautan reset kata sandi ini akan kadaluarsa dalam 60 menit.</p>
            <p>Jika Anda tidak meminta reset kata sandi, abaikan email ini dan akun Anda akan tetap aman.</p>

            <p class="url-text">
                Jika Anda kesulitan mengklik tombol "Reset Kata Sandi", salin dan tempel URL di bawah ini ke peramban web Anda:<br>
                <a href="{{ $resetLink }}">{{ $resetLink }}</a>
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Hak cipta dilindungi undang-undang.</p>
        </div>
    </div>
</body>
</html>
