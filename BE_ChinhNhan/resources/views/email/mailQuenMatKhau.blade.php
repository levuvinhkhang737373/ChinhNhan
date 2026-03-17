<!-- Giao diện email quên mật khẩu -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yêu cầu đặt lại mật khẩu</title>
    <style>
        .container {
            max-width: 500px;
            margin: auto;
            padding: 24px;
            background: #f9f9f9;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 16px;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Yêu cầu đặt lại mật khẩu</h2>
        <p>Xin chào,</p>
        <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản của mình. Vui lòng nhấn vào nút bên dưới để chuyển đến trang đặt lại mật khẩu:</p>
        <a href="{{ $resetLink }}" class="btn">Đặt lại mật khẩu</a>
        <p>Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>
        <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
    </div>
</body>
</html>