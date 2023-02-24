<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>{{ config('app.name', 'labchess') }}</title>
    <meta name="description" content="Reset Password">
    <style>
        a:hover {
            text-decoration: underline !important;
        }
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #edf2f7;" leftmargin="0">
<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#edf2f7"
       style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
    <tr>
        <td>
            <table style="background-color: #edf2f7; max-width:630px;  margin:0 auto;" width="100%" border="0"
                   align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <a href="{{URL::to('/')}}" title="labchess" target="_blank" style="color: #3d4852;font-size: 30px;text-decoration: none !important;">
                            labchess
                        </a>
                    </td>
                </tr>
                <tr>
                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                </tr>
                <tr>
                    <td>
                        <!-- Container -->
                        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:630px;background:#fff;">
                            <tr>
                                <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                            </tr>
                            <tr>
                                <td style="padding:0 35px;">
                                    <h1 style="color: #3d4852;font-size: 19px;font-weight: bold;">
                                        Hello!
                                    </h1>
                                    <p style="font-size: 16px;line-height: 1.5em;margin:0; color: #718096;">
                                        You are receiving this email because we received a password reset request for
                                        your account.
                                    </p>

                                    <table border="0" align="center" cellpadding="0" cellspacing="0" style="text-align: center;">
                                        <tr style="text-align: center">
                                            <a href="{{URL::to('/')}}/reset-password/{{$token}}" style="background-color: #3D96F2;text-decoration:none !important; font-weight:500; margin:35px auto; color:#fff;font-size:14px;padding:10px 24px;display:inline-block; text-align: center !important;">
                                                Reset Password
                                            </a>
                                        </tr>
                                    </table>

                                    <p style="font-size: 16px;line-height: 1.5em;margin:0; color: #718096;">
                                        This password reset link will expire in 60 minutes.
                                        <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                                        <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                                        If you did not request a password reset, no further action is required.
                                        <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                                        <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                                        Regards,<br>
                                        labchess
                                    </p>
                                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                                    <span style="width: 100%; display: block; margin: 15px auto; border-top: 1px solid #e8e5ef;"></span>
                                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                                    <p style="font-size: 13px;line-height: 1.5em;margin:0; color: #718096;">
                                        If you're having trouble clicking the "Reset Password" button, copy and paste
                                        the URL below into your web browser: {{URL::to('/')}}/reset-password/{{$token}}
                                    </p>
                                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                                </td>
                            </tr>
                            <tr>
                                <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                            </tr>
                        </table>
                    </td>
                <tr>
                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <p style="font-size:12px; color:#b0adc5; line-height:18px; margin:0 0 0;">
                            &copy; {{\Carbon\Carbon::now()->format('Y')}} labchess. All rights reserved.</p>
                    </td>
                </tr>
                <tr>
                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                    <br style="user-select: none; -ms-user-select: none;-moz-user-select: none;-webkit-user-select: none;-webkit-touch-callout: none;">
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
