<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>{{ config('app.name', 'labchess') }}</title>
    <meta name="description" content="New labchess follower">
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px;" leftmargin="0">

<p style="font-size: 15px; margin-bottom: 15px">Hello {{$profile->username}}, the labchess member with username {{$followerUsername}} started following you!</p>

<p>
    <a href="https://labchess.com/" style="background-color: #3D96F2;color: white;border-radius: 6px; text-decoration: none; padding: 12px;display: inline-block;font-size: 15px;">
        VISIT LABCHESS
    </a>
</p>

<!-- Unsubscribe -->
<p style="margin-top: 30px; font-size: 12px; text-decoration: underline">
    <a style="color: #9ca3af;" href="https://labchess.com/unsubscribe/{{$profile->uuid}}">Unsubscribe</a>
</p>

</body>
</html>
