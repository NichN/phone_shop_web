<!DOCTYPE html>
<html>
<head>
    <title>Password Change Verification</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2>Password Change Verification</h2>
        <p>You have requested to change your password. Please use the following verification code to complete the process:</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0;">
            <h1 style="margin: 0; color: #70000E; letter-spacing: 5px;">{{ $code }}</h1>
        </div>

        <p>This code will expire in 10 minutes.</p>
        
        <p>If you did not request a password change, please ignore this email or contact support if you have concerns.</p>
        
        <hr style="border: 1px solid #eee; margin: 20px 0;">
        
        <p style="color: #666; font-size: 12px;">
            This is an automated message, please do not reply to this email.
        </p>
    </div>
</body>
</html> 