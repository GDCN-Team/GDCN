<x-layout>

    @php
        /** @var \App\Models\GameAccount $account */
        $account = Auth::user();
        $user = $account->user;

        $chest1time = strtotime($account->user->score->chest1time ?? 0) + config('game.reward.small.wait', 3600);
        $chest2time = strtotime($account->user->score->chest2time ?? 0) + config('game.reward.big.wait', 14400);

        $friends = $account->friends;
        $messages = $account->messages;
    @endphp

    <a-row :gutter="[10, 10]">
        <a-col span="24">
            <a-card title="欢迎! {{ $account->name }}">
                <a-row :gutter="[10, 10]">
                    <a-col :md="12" span="24">
                        <a-button href="{{ route('dashboard.profile') }}">个人资料</a-button>
                        <a-button href="{{ route('dashboard.account.setting') }}">账号设置</a-button>
                        <a-button href="{{ route('dashboard.account.update.password') }}">更改密码</a-button>
                        <logout></logout>
                    </a-col>
                    <a-col :md="6" span="24">
                        <a-row :gutter="[10, 10]">
                            <strong>账号信息</strong>
                            <hr>
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
                        </a-row>
                    </a-col>

                    @if($user)
                        <a-col :md="6" span="24">
                            <a-row :gutter="[10, 10]">
                                <strong>用户信息:</strong>
                                <hr>
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
                            </a-row>
                        </a-col>
                    @endif
                </a-row>
            </a-card>
        </a-col>
        <a-col :md="12" span="24">
            <a-card title="好友">
                <a-list item-layout="horizontal">
                    @foreach($friends as $friend)
                        <a-list-item>
                            {{ $friend->name }}
                        </a-list-item>
                    @endforeach
                </a-list>
            </a-card>
        </a-col>
        <a-col :md="12" span="24">
            <a-card title="私信">
                <a-list item-layout="horizontal">
                    @foreach($messages as $message)
                        <a-list-item>
                            {{ $message->subject }} - {{ $message->sender->id }}
                        </a-list-item>
                    @endforeach
                </a-list>
            </a-card>
        </a-col>
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
        template: `<a-button @click="logout">登出</a-button>`,
        methods: {
            logout: function () {
                window.$request({
                    url: '{{ route('web.api.v1.logout') }}',
                    data: this.form,
                    default_failed_text: '登出失败',
                    redirect: '{{ route('web.api.v1.login') }}'
                });
            }
        }
    });
</script>
