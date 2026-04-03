<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>

<body style="margin:0; padding:0; background:#f5f5f5; font-family:Arial, sans-serif;">

    <!-- Outer Container -->
    <table align="center" width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0; background:#f5f5f5;">
        <tr>
            <td align="center">

                <!-- Card -->
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px;">

                    <!-- Header Bar -->
                    <tr>
                        <td bgcolor="#F59E42" style="padding:25px; text-align:center; border-top-left-radius:12px; border-top-right-radius:12px;">
                            <h1 style="margin:0; color:#000000; font-size:26px; font-weight:bold; font-family:Arial, sans-serif;">
                                Ticket Portal - Email Verification
                            </h1>
                        </td>
                    </tr>

                    <!-- Main Body -->
                    <tr>
                        <td style="padding:35px 40px; color:#333333; font-size:16px; line-height:1.7;">

                            <p style="margin-top:0; margin-bottom:15px;">Hello,</p>

                            <p style="margin-bottom:15px;">
                                Welcome to <strong style="color:#000000;">Support Ticket Portal</strong>! We're excited to have you onboard.
                            </p>

                            <p style="margin-bottom:12px;">Here is your verification code:</p>

                            <!-- Verification Code Box (Table for Gmail) -->
                            <table align="center" cellpadding="0" cellspacing="0" style="background-color:#F59E42; border-radius:8px; margin:20px 0;">
                                <tr>
                                    <td align="center" style="color:#000000; font-size:26px; font-weight:bold; letter-spacing:6px; padding:15px 28px;">
                                        {{ $verificationCode }}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-bottom:15px;">
                                Enter this code in the verification page to activate your account. For your security, this code will expire shortly.
                            </p>

                            <!-- Divider -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:25px 0;">
                                <tr>
                                    <td style="height:1px; background:#e5e5e5; line-height:1px; font-size:1px;">&nbsp;</td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#777777; margin:0;">
                                If you did not attempt to create an account, you can safely ignore this email.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#fafafa" style="text-align:center; padding:20px; font-size:12px; color:#777777; border-bottom-left-radius:12px; border-bottom-right-radius:12px;">
                            © {{ date('Y') }} Ticket Portal. All rights reserved.
                        </td>
                    </tr>

                </table>
                <!-- End Card -->

            </td>
        </tr>
    </table>
    <!-- End Outer Container -->

</body>
</html>
