<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #3B82F6;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
        }

        .credentials {
            background: #fff;
            border: 2px dashed #3B82F6;
            padding: 20px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #3B82F6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to EduTech Benin!</h1>
        </div>

        <div class="content">
            <h2>Dear {{ $manager->name }},</h2>

            <p>Thank you for registering <strong>{{ $school->name }}</strong> with EduTech Benin!</p>

            <p>Your school registration has been received and is currently <strong>pending approval</strong> by our
                administration team.</p>

            <div class="credentials">
                <h3>Your Login Credentials</h3>
                <p><strong>Email:</strong> {{ $manager->email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
                <p style="color: #ef4444; font-size: 14px;">
                    ⚠️ Please save these credentials securely. You can change your password after your first login.
                </p>
            </div>

            <h3>What's Next?</h3>
            <ol>
                <li>Our admin team will review your school registration</li>
                <li>You'll receive an email notification once your school is approved or if additional information is
                    needed</li>
                <li>After approval, you can log in and start configuring your school</li>
            </ol>

            <p style="margin-top: 30px;">
                <a href="{{ url('/login') }}" class="button">Go to Login Page</a>
            </p>

            <p style="margin-top: 20px; font-size: 14px; color: #666;">
                If you have any questions, please contact our support team at support@edutech.bj
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} EduTech Benin. All rights reserved.</p>
        </div>
    </div>
</body>

</html>