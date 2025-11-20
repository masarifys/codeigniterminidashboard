<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: #E8D1C5;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔐 Password Reset Request Exputra Billing</h1>
    </div>
    
    <div class="content">
        <p>Hello <strong><?= esc($full_name) ?></strong>,</p>
        
        <p>You recently requested to reset your password. Click the button below to reset it:</p>
        
        <div style="text-align: center;">
            <a href="<?= $reset_link ?>" class="button">Reset My Password</a>
        </div>
        
        <p>Or copy and paste this link into your browser:</p>
        <p style="background: #e9ecef; padding: 10px; border-radius: 5px; word-break: break-all;">
            <a href="<?= $reset_link ?>"><?= $reset_link ?></a>
        </p>
        
        <div class="warning">
            <strong>Important:</strong>
            <ul style="margin: 10px 0;">
                <li>This password reset link will expire in 1 hour</li>
                <li>If you didn't request this reset, please ignore this email</li>
                <li>For security reasons, please don't share this link with anyone</li>
            </ul>
        </div>
        
        <p>If you're having trouble clicking the button, you can also visit the login page and click "Forgot Password" to request a new reset link.</p>
        
        <p>Best regards,<br>
        <strong>Exputra Billing Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply to this email.</p>
        <p>&copy; <?= date('Y') ?> Your App Name. All rights reserved.</p>
    </div>
</body>
</html>