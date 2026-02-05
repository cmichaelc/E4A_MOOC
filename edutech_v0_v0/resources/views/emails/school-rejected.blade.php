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
            background: #EF4444;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
        }

        .reason-box {
            background: #FEE2E2;
            border-left: 4px solid #EF4444;
            padding: 15px;
            margin: 20px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #3B82F6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Registration Update Required</h1>
        </div>

        <div class="content">
            <h2>School Registration Needs Revision</h2>

            <p>Dear {{ $school->manager->name }},</p>

            <p>Thank you for registering <strong>{{ $school->name }}</strong> with EduTech Benin.</p>

            <p>After reviewing your registration, we need you to make some adjustments before we can approve your
                school.</p>

            <div class="reason-box">
                <h3 style="margin-top: 0; color: #DC2626;">Reason for Rejection:</h3>
                <p style="margin-bottom: 0;">{{ $school->rejection_reason }}</p>
            </div>

            <h3>What To Do Next:</h3>
            <ol>
                <li>Log in to your account</li>
                <li>Review the feedback above</li>
                <li>Edit your school information</li>
                <li>Resubmit your registration for approval</li>
            </ol>

            <p style="margin-top: 30px; text-align: center;">
                <a href="{{ url('/login') }}" class="button">Login to Edit Registration</a>
            </p>

            <p style="margin-top: 20px; font-size: 14px; color: #666;">
                If you have any questions about the required changes, please contact us at support@edutech.bj
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} EduTech Benin. All rights reserved.</p>
        </div>
    </div>
</body>

</html>