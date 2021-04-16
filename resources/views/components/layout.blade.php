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
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @if( $notification = session('notification') )
        <script>
            $(function () {
                app.$notification.open( @json($notification, JSON_THROW_ON_ERROR) );
            });
        </script>
    @endif
    <title>{{ config('app.name') }}</title>
</head>
<body class="bg-gray-800 font-sans">
<div id="app">
    <a-layout class="h-screen">
        <x-sider></x-sider>
        <a-layout>
            <a-layout-content {{ $attributes->merge(['class' => 'bg-gray-800 overflow-auto text-white p-3']) }}>
                {{ $slot }}
            </a-layout-content>
            <a-layout-footer>
                GDCN | 2020 - {{ date('Y') }} | <a href="//www.beian.miit.gov.cn">吉ICP备18006293号</a>
            </a-layout-footer>
        </a-layout>
    </a-layout>
</div>
</body>
</html>

@auth
    <script id="logout" type="text/x-template">
        <a @click="logout">
            <a-icon type="logout"></a-icon>
            <span>登出</span>
        </a>
    </script>

    <script>
        window.Vue.component('logout', {
            template: `#logout`,
            methods: {
                logout: function () {
                    window.$request({
                        url: '{{ route('web.api.v1.logout') }}',
                        data: this.form,
                        default_failed_text: '登出失败',
                        redirect: '{{ route('login') }}'
                    });
                }
            }
        });
    </script>
@endauth
