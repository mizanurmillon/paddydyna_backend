<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Account Created</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #006850;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .login-details {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 20px 0;
        }

        .login-details p {
            margin: 10px 0;
        }

        .cta-button {
            display: inline-block;
            background-color: #006850;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            margin: 20px auto;
            display: block;
            width: fit-content;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="container">
            <h1>Welcome to Our Website!</h1>
            <p>Dear {{ $data['name'] }},</p>
            <p>We are excited to let you know that your account has been successfully created! Here are your login
                details:</p>

            <div class="login-details">
                <p><strong>Email:</strong> {{ $data['email'] }}</p>
                <p><strong>Password:</strong> {{ $data['password'] }}</p>
            </div>

            <p>Thank you for joining with us. We look forward to serving you.</p>

            <p>If you have any questions or need assistance, feel free to contact our support team.</p>
            <p>Thank you for choosing us!</p>
        </div>
    </div>
</body>

</html>