<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - PRMSU CCIT Student Feedback System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #494850 0%, #5a5a6a 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            padding: 10px;
            margin: 0 auto 20px;
            display: block;
        }
        
        .logo-fallback {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            padding: 10px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #494850;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        
        .email-header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #494850;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #98AAE7 0%, #7a8fd8 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(152, 170, 231, 0.3);
        }
        
        .reset-button:hover {
            background: linear-gradient(135deg, #7a8fd8 0%, #98AAE7 100%);
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
            color: #856404;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        
        .footer a {
            color: #98AAE7;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #dee2e6 50%, transparent 100%);
            margin: 20px 0;
        }
        
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .email-header {
                padding: 20px 15px;
            }
            
            .email-body {
                padding: 30px 20px;
            }
            
            .email-header h1 {
                font-size: 24px;
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
    <div class="email-container">
        <div class="email-header">
            <h1>PRMSU CCIT</h1>
            <p>Student Feedback System</p>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                Hello!
            </div>
            
            <div class="message">
                You are receiving this email because we received a password reset request for your account.
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="reset-button">
                    Reset Password
                </a>
            </div>
            
            <div class="warning">
                <strong>Important:</strong> This password reset link will expire in 60 minutes.
            </div>
            
            <div class="message">
                If you did not request a password reset, no further action is required.
            </div>
            
            <div class="divider"></div>
            
            <div class="message" style="font-size: 14px; color: #6c757d;">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                <br><br>
                <a href="{{ $actionUrl }}" style="color: #98AAE7; word-break: break-all;">{{ $actionUrl }}</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Regards,</strong></p>
            <p>PRMSU CCIT Student Feedback System Team</p>
            <p>
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </p>
            <p style="font-size: 12px; margin-top: 15px;">
                © {{ date('Y') }} PRMSU CCIT. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
