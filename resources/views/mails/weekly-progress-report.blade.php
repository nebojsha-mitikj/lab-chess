<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>{{ config('app.name', 'labchess') }}</title>
    <meta name="description" content="Weekly labchess progress report">
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px;" leftmargin="0">

    @if($earnedXP > 0)
        <p style="font-size: 15px;">Hello {{$username}}, your weekly progress report is ready! 🎉</p>
    @else
        <p style="font-size: 15px;">Hello {{$username}}, it looks like you haven't made any progress this week... 😔</p>
    @endif

    <ul style="font-size: 15px;">
        <li><b style="font-weight: bold;">{{$earnedXP}}</b> XP earned.</li>
        <li><b style="font-weight: bold;">{{$completedLectures}}</b> completed endgame lectures.</li>
        <li><b style="font-weight: bold;">{{$completedTrainers}}</b> solved endgame trainer positions.</li>
        <li><b style="font-weight: bold;">{{$daysActiveInWeek}}</b> out of <b style="font-weight: bold;">7</b> days active.</li>
    </ul>

    <p style="font-size: 15px">Practice makes perfect!</p>

    <p>
        <a href="https://labchess.com/" style="background-color: #3D96F2;color: white;border-radius: 6px; text-decoration: none; padding: 12px;display: inline-block;font-size: 15px;">
            @if($earnedXP > 0)
                CONTINUE LEARNING
            @else
                START LEARNING NOW
            @endif
        </a>
    </p>

    @if($earnedXP <= 0)
        <p style="font-size: 14px; color: #9ca3af; margin-top: 10px">(We'll stop sending you weekly reports for now)</p>
    @endif

    <!-- Unsubscribe -->
    <p style="margin-top: 30px; font-size: 12px; text-decoration: underline">
        <a style="color: #9ca3af;" href="https://labchess.com/unsubscribe/{{$uuid}}">Unsubscribe</a>
    </p>
</body>
</html>
