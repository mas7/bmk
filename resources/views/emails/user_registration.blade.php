<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            color: #555;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 560px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #009688;
            /* Teal color */
            font-size: 24px;
            text-align: center;
            margin-bottom: 24px;
        }

        p,
        ul li {
            font-size: 16px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .security-note {
            background-color: #e3f2fd;
            /* Light blue */
            color: #333;
            padding: 10px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 14px;
        }


        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to BMK</h1>
        <p>Hello {{ $user->name }},</p>
        <p>Thank you for registering with us. We're excited to have you on board.</p>
        <p>Your registration details are as follows:</p>
        <ul>
            <li><strong>Name:</strong> {{ $user->name }}</li>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Phone:</strong> {{ $user->phone_number }}</li>
            <li>
                <strong>Password:</strong> {{ $user->plainPassword }}
                <div class="security-note">
                    <strong>Note:</strong> For your security, please change your password after logging in from your
                    profile page.
                </div>
            </li>
        </ul>
        <p>If you have any questions, feel free to contact us at any time.</p>
        <div class="footer">
            Best regards,<br>
            BMK Team
        </div>
    </div>
</body>

</html>
