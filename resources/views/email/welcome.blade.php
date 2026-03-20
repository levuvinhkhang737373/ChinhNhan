<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng bạn đến với Chính Nhân</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 0 30px 0; background-color: #f6f9fc;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e1e8ed;">
                    <!-- Banner/Header -->
                    <tr>
                        <td align="center" style="padding: 25px 40px; background-color: #ffffff;">
                            <img src="https://api.chinhnhan.com/uploads/66f1249bd1287.png" alt="Chính Nhân Logo" width="180" style="display: block;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 15px 40px; background: linear-gradient(135deg, #FFB92D 0%, #FF8C00 100%);">
                            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Chào mừng thành viên mới!</h1>
                        </td>
                    </tr>

                    <!-- Nội dung chính -->
                    <tr>
                        <td style="padding: 40px;">
                            <div style="font-size: 16px; line-height: 1.6; color: #334155;">
                                {!! $data['html'] ?? '' !!}
                            </div>

                            @if(isset($data['link_activate']))
                                <div style="text-align: center; padding: 30px 0;">
                                    <a href="{{ $data['link_activate'] }}" style="display: inline-block; padding: 14px 30px; background: linear-gradient(135deg, #FFB92D 0%, #FF8C00 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 6px rgba(255, 140, 0, 0.2);">
                                        ĐĂNG NHẬP TÀI KHOẢN
                                    </a>
                                </div>
                            @endif

                            <div style="margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                                <p style="margin: 0 0 10px 0; font-size: 14px; color: #64748b;">
                                    Nếu bạn có bất kỳ thắc mắc nào, đừng ngần ngại liên hệ với chúng tôi qua hotline <strong>1900 5712 00</strong>.
                                </p>
                                <p style="margin: 0; font-size: 14px; color: #1e293b; font-weight: 600;">
                                    Trân trọng,<br>
                                    <span style="color: #FF8C00;">Đội ngũ Chính Nhân</span>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Cam kết / Tip -->
                    <tr>
                        <td style="padding: 0 40px 30px 40px;">
                            <div style="background-color: #fff9eb; border-left: 4px solid #FFB92D; padding: 15px; font-size: 13px; color: #92400e; border-radius: 4px;">
                                <p style="margin: 0;"><strong>Mẹo:</strong> Bạn có thể theo dõi đơn hàng và cập nhật các chương trình khuyến mãi mới nhất ngay trong tài khoản cá nhân của mình.</p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 40px; background-color: #f8fafc; border-top: 1px solid #edf2f7;">
                            <p style="margin: 0 0 15px 0; font-size: 14px; font-weight: 700; color: #1e293b;">CÔNG TY TNHH CÔNG NGHỆ CHÍNH NHÂN</p>
                            <p style="margin: 0 0 5px 0; font-size: 12px; color: #64748b;">MST: 0301279545 | Hotline: 1900 5712 00</p>
                            <p style="margin: 0 0 15px 0; font-size: 12px; color: #64748b;">Địa chỉ: 245A Trần Quang Khải, P. Tân Định, Quận 1, TP.HCM</p>
                            <div style="border-top: 1px solid #e2e8f0; padding-top: 15px;">
                                <a href="https://chinhnhan.vn" style="color: #FF8C00; text-decoration: none; font-size: 12px; font-weight: 700;">CHINHNHAN.VN</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
