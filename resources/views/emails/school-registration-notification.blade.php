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

        .school-info {
            background: #fff;
            border-left: 4px solid #EF4444;
            padding: 15px;
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
            background: #EF4444;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>New School Registration</h1>
        </div>

        <div class="content">
            <h2>Admin Notification</h2>

            <p>A new school has registered and is awaiting your approval.</p>

            <div class="school-info">
                <h3>School Details:</h3>
                <p><strong>Name:</strong> {{ $school->name }}</p>
                <p><strong>Address:</strong> {{ $school->address }}</p>
                <p><strong>Status:</strong> {{ $school->status }}</p>

                <h3 style="margin-top: 20px;">Manager Details:</h3>
                <p><strong>Name:</strong> {{ $manager->name }}</p>
                <p><strong>Email:</strong> {{ $manager->email }}</p>
            </div>

            <p style="margin-top: 30px;">
                Please review this registration and approve or reject it from your admin dashboard.
            </p>

            <p style="margin-top: 20px;">
                <a href="{{ url('/admin/schools/pending') }}" class="button">Review Registration</a>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} E4A_MOOC Benin - Admin Panel</p>
        </div>
    </div>
</body>

</html>