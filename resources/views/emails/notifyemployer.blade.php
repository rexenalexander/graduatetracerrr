<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Feedback Form!</title>
</head>     
<body>
    <div style="padding: 20px;">
        <p style="font-size: 13px; color: #333; margin: 0;">Dear <b>{{ $data }}</b>, </p>
        <p style="font-size: 13px; margin-top: -10px; color: #333">
            {{ $body }}
        </p>
        <p style="font-size: 13px; margin-top: 15px; color: #333">
            {{ $footer }}
        </p>
        <p style="font-size: 11px; margin-top: 10px; color: #333; font-style: italic;">This is an automated email, do not reply.</p>
    </div>
</body>
</html>         