<!doctype html>
<html lang="{{ strtr(config('app.locale'), '_', '-') }}">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="GDCN,Geometry Dash Chinese,几何冲刺,GD,渣渣120,私服,好玩">
    <meta name="description" content="GDCN是渣渣120开的一个GD(Geometry Dash)(几何冲刺)私服, GDCN是Geometry Dash Chinese的缩写">
    <meta name="author" content="渣渣120,WOSHIZHAZHA120@qq.com"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content=" {{ csrf_token () }}">
    @if (!App::isLocal())
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"/>
        <script>window.location.protocol === 'https:' || (window.location.href = window.location.href.replace('http', 'https'))</script>
    @endif
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @csrf
    <link rel="icon" href="https://cdn.geometrydashchinese.com/static/images/logo.png">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>{{ config('app.name') }}</title>
</head>
<body>
@inertia
</body>
</html>
