<!DOCTYPE html>
<html>
<head>
    <title>Password Reset - Tay Meng Phone Shop</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2>Password Reset Request</h2>
        
        @if($userName)
            <p>Hello {{ $userName }},</p>
        @endif
        
        <p>You have requested to reset your password for your Tay Meng Phone Shop account. Click the button below to reset your password:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/reset-password?token=' . $token) }}" 
               style="background-color: #70000E; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Reset Password
            </a>
        </div>

        <p>This link will expire in 1 hour for security reasons.</p>
        
        <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
        
        <hr style="border: 1px solid #eee; margin: 20px 0;">
        
        <p style="color: #666; font-size: 12px;">
            This is an automated message from Tay Meng Phone Shop, please do not reply to this email.
        </p>
    </div>
</body>
</html> 