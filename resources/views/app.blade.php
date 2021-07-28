<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="GDCN,Geometry Dash Chinese,几何冲刺,GD,渣渣120,私服,好玩">
    <meta name="description" content="GDCN是渣渣120开的一个GD(Geometry Dash)(几何冲刺)私服, GDCN是Geometry Dash Chinese的缩写">
    <meta name="author" content="渣渣120,WOSHIZHAZHA120@qq.com"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @csrf
    <link rel="icon" href="https://cdn.geometrydashchinese.com/static/gdcn/images/logo.png">
    <link rel="stylesheet" href="{{ asset('css/gdcn.css') }}">
    <script src="{{ asset('js/gdcn.js') }}"></script>
    <title>{{ config('app.name') }}</title>
</head>
<body>
<div data-component="{{ $component }}" id="app">

</div>
</body>
</html>
