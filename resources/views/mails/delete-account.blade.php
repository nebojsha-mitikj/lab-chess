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

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px;" leftmargin="0">

<p>Thank you for using labchess! We're sad to see you go.</p>

<p>You've requested us to delete your labchess account ({{$email}}) and all your personal data stored by labchess.</p>

<p>If you've changed your mind: Just ignore the email.</p>

<p>If you still want to delete your data, click the "Delete my data" link below. The deletion is irreversible.</p>

<p>Delete my data: <a href="{{$link}}">{{$link}}</a></p>

</body>
</html>
