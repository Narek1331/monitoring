<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Уведомление</title>
</head>
<body>
    <h1>{{ config('app.name') }}</h1>

    <div style="font-size: 16px; line-height: 1.5;">
        {!! $data !!}
    </div>
</body>
</html>
