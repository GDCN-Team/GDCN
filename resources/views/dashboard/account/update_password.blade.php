<x-layout>
    <form></form>
</x-layout>

<script id="form" type="text/x-template">
    <a-card title="更改密码">
        <a-form-model :model="setting" @submit="update" @submit.native.prevent>
            <a-form-model-item>
                <a-input v-model="setting.password" auto-complete="new-password" type="password" placeholder="密码">
                    <a-icon slot="prefix" type="lock"></a-icon>
                </a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-button type="primary" html-type="submit" :disabled="!setting.password">
                    更新
                </a-button>
            </a-form-model-item>
        </a-form-model>
    </a-card>
</script>

<script>
    window.Vue.component('setting', {
        template: `#setting`,
        data: function () {
            return {
                setting: {
                    password: ''
                },

                request: {
                    url: '{{ route('web.api.v1.account.update.password') }}',
                    data: this.setting,
                    loading: false,
                    default_failed_text: '更新失败',
                    redirect: '{{ route('dashboard.profile') }}'
                }
            }
        },
        methods: {
            update: function () {
                this.request.data = this.setting;
                window.$request(this.request);
            }
        }
    })
</script>
