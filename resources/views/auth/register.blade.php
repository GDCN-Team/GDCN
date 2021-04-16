<x-layout>
    <register></register>
</x-layout>

<script id="register" type="text/x-template">
    <a-card title="注册">
        <a-form-model :model="form" @submit="register" @submit.native.prevent>
            <a-form-model-item>
                <a-input v-model="form.name" placeholder="用户名">
                    <a-icon slot="prefix" type="user"></a-icon>
                </a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-input v-model="form.password" type="password" placeholder="密码">
                    <a-icon slot="prefix" type="lock"></a-icon>
                </a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-input v-model="form.email" type="email" placeholder="邮箱">
                    <a-icon slot="prefix" type="mail"></a-icon>
                </a-input>

                <a href="{{ route('login') }}">已有账号</a>
            </a-form-model-item>
            <a-form-model-item>
                <a-button type="primary" :loading="request.loading" html-type="submit"
                          :disabled="!form.name || !form.password || !form.email">
                    注册
                </a-button>
            </a-form-model-item>
        </a-form-model>
    </a-card>
</script>

<script>
    window.Vue.component('register', {
        template: `#register`,
        data: function () {
            return {
                form: {
                    name: '',
                    password: '',
                    email: ''
                },

                request: {
                    url: '{{ route('web.api.v1.register') }}',
                    data: this.form,
                    loading: false,
                    default_failed_text: '注册失败',
                    redirect: '{{ route('home') }}'
                }
            }
        },
        methods: {
            register: function () {
                this.request.data = this.form;
                window.$request(this.request);
            }
        }
    })
</script>
