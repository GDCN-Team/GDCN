<x-layout>
    <a-row :gutter="[10, 10]">
        <a-col :md="12" span="24">
            @php( $account = Auth::user() )
            <a-card title="账号信息">
                <h4>
                    账号ID: {{ $account->id }}
                    <br>
                    用户名: {{ $account->name }}
                    <br>
                    邮箱: {{ $account->email }}
                    <br>
                    注册时间: {{ $account->created_at }}
                    <br>
                    邮箱验证时间: {{ $account->email_verified_at }}
                    @if (!$account->email_verified_at)
                        <re-send-email></re-send-email>
                    @endif
                    <br>
                    <a href="{{ route('dashboard.account.setting') }}">账号设置</a>
                    <a href="{{ route('dashboard.account.update.password') }}">更改密码</a>
                    <logout></logout>
                </h4>
            </a-card>
        </a-col>
        @if( $user = $account->user )
            <a-col :md="12" span="24">
                <a-card title="用户信息">
                    <h4>
                        用户ID: {{ $user->id }}
                        <br>
                        用户名: {{ $user->name }}
                        <br>
                        用户唯一标识码: {{ $user->uuid }}
                        <br>
                        设备唯一标识码: <span
                            class="bg-black p-px hover:text-white">{{ $user->udid }}</span>
                        <br>
                        创建时间: {{ $user->created_at }}
                        <br><br>
                    </h4>
                </a-card>
            </a-col>
        @endif
    </a-row>
</x-layout>

<script>
    window.Vue.component('re-send-email', {
        template: `<a @click="resend" href="javascript:">重发验证邮件</a>`,
        methods: {
            resend: function () {
                window.$request({
                    url: '{{ route('web.api.v1.account.resend.email') }}',
                    default_failed_text: '重发失败'
                });
            }
        }
    });

    window.Vue.component('logout', {
        template: `<a @click="logout" href="javascript:">登出</a>`,
        methods: {
            logout: function () {
                window.$request({
                    url: '{{ route('web.api.v1.logout') }}',
                    default_failed_text: '登出失败',
                    redirect: '{{ route('login') }}'
                });
            }
        }
    });
</script>
