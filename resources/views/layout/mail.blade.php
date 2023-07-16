<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <style>
        body {
            font-family: sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
