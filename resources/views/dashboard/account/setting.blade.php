<x-layout>
    <setting></setting>
</x-layout>

<script id="setting" type="text/x-template">
    <a-card title="账号设置">
        <a-form-model :model="setting" @submit="update" @submit.native.prevent>
            <a-form-model-item>
                <a-input v-model="setting.name" placeholder="用户名">
                    <a-icon slot="prefix" type="user"></a-icon>
                </a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-input v-model="setting.email" type="email" placeholder="邮箱">
                    <a-icon slot="prefix" type="mail"></a-icon>
                </a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-button type="primary" html-type="submit" :loading="request.loading" :disabled="!setting.name || !setting.email">
                    更新
                </a-button>
            </a-form-model-item>
        </a-form-model>
    </a-card>
</script>

<script>
    @php( $account = Auth::user() )

    window.Vue.component('setting', {
        template: `#setting`,
        data: function () {
            return {
                setting: {
                    name: '{{ $account->name }}',
                    email: '{{ $account->email }}'
                },

                request: {
                    url: '{{ route('web.api.v1.account.setting.update') }}',
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
