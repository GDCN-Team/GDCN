<a-layout-sider breakpoint="md" collapsed-width="0">
    <a-menu theme="dark" mode="vertical" :default-selected-keys="['{{ Route::currentRouteName() }}']">

        <a-menu-item key="home">
            <a href="{{ route('home') }}">
                <a-icon type="home"></a-icon>
                <span>主页</span>
            </a>
        </a-menu-item>

        <a-menu-item key="dashboard.home">
            <a href="{{ route('dashboard.home') }}">
                <a-icon type="dashboard"></a-icon>
                <span>Dashboard</span>
            </a>
        </a-menu-item>

        <a-menu-item key="tools.home">
            <a href="{{ route('tools.home') }}">
                <a-icon type="tool"></a-icon>
                <span>Tools</span>
            </a>
        </a-menu-item>

        @auth
            @php
                /** @var \App\Models\GameAccount $account */
                $account = Auth::user()
            @endphp

            <a-sub-menu key="dashboard">
                <a-menu-item slot="title">{{ $account->name }}</a-menu-item>
                <a-menu-item key="dashboard.profile">
                    <a href="{{ route('dashboard.profile') }}">
                        <a-icon type="user"></a-icon>
                        <span>个人资料</span>
                    </a>
                </a-menu-item>
                <a-menu-item key="dashboard.account.setting">
                    <a href="{{ route('dashboard.account.setting') }}">
                        <a-icon type="setting"></a-icon>
                        <span>账号设置</span>
                    </a>
                </a-menu-item>
                <a-menu-item key="auth.logout">
                    <logout></logout>
                </a-menu-item>
            </a-sub-menu>
        @else
            <a-menu-item key="login">
                <a href="{{ route('login') }}">
                    <a-icon type="login"></a-icon>
                    <span>登录</span>
                </a>
            </a-menu-item>
        @endauth

    </a-menu>
</a-layout-sider>
