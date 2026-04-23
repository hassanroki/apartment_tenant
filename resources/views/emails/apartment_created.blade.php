<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Apartment</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <!-- Card -->
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; margin-top:30px; border-radius:10px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.05);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#4f46e5; padding:20px; text-align:center; color:#ffffff;">
                            <h2 style="margin:0;">🏢 New Apartment Added</h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333;">
                            <p style="font-size:16px;">Hello,</p>

                            <p style="font-size:15px; color:#555;">
                                A new apartment has been added to the system. Here are the details:
                            </p>

                            <table width="100%" cellpadding="10" cellspacing="0" style="margin-top:20px; border:1px solid #eee; border-radius:6px;">
                                <tr style="background:#f9fafb;">
                                    <td><strong>Apartment Name</strong></td>
                                    <td>{{ $apartment->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Rent</strong></td>
                                    <td>৳ {{ number_format($apartment->rent) }}</td>
                                </tr>
                            </table>

                            <!-- Button -->
                            <div style="text-align:center; margin-top:30px;">
                                <a href="#" style="background:#4f46e5; color:#fff; padding:12px 25px; text-decoration:none; border-radius:6px; font-size:14px;">
                                    View Apartment
                                </a>
                            </div>

                            <p style="margin-top:30px; font-size:13px; color:#888;">
                                Thanks,<br>
                                {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f1f1f1; padding:15px; text-align:center; font-size:12px; color:#777;">
                            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
