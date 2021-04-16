<x-layout>
    <login></login>
</x-layout>

<script id="login" type="text/x-template">
    <a-card title="登录">
        <a-form-model :model="form" @submit="login" @submit.native.prevent>
            <a-form-model-item>
                <a-input v-model="form.name" placeholder="用户名">
                    <a-icon slot="prefix" type="user"></a-icon>
                </a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-input v-model="form.password" type="password" placeholder="密码">
                    <a-icon slot="prefix" type="lock"></a-icon>
                </a-input>

                <a href="{{ route('register') }}">注册账号</a>
            </a-form-model-item>
            <a-form-model-item>
                <a-button type="primary" html-type="submit" :loading="request.loading" :disabled="!form.name || !form.password">
                    登录
                </a-button>
            </a-form-model-item>
        </a-form-model>
    </a-card>
</script>

<script>
    window.Vue.component('login', {
        template: `#login`,
        data: function () {
            return {
                form: {
                    name: '',
                    password: ''
                },

                request: {
                    default_failed_text: '您的用户名或密码错误',
                    url: '{{ route('web.api.v1.login') }}',
                    data: this.form,
                    loading: false,
                    redirect: '{{ session('url.intended', '/') }}'
                }
            }
        },
        methods: {
            login: function () {
                this.request.data = this.form;
                window.$request(this.request);
            }
        }
    })
</script>
