<x-layout>
    <page></page>
</x-layout>

<script id="page" type="text/x-template">
    <a-card title="关卡搬进">
        <a-form-model :model="form" @submit="submit" @submit.native.prevent>
            <a-form-model-item label="服务器">
                <a-select v-model="form.server">
                    <a-select-option value="official">
                        官服
                    </a-select-option>
                    <a-select-option value="custom">
                        自定义
                    </a-select-option>
                </a-select>
            </a-form-model-item>
            <a-form-model-item v-if="form.server === 'custom'">
                <a-input v-model="form.custom_server_url" type="url" placeholder="自定义服务器地址"></a-input>
                <span>示例地址（官服）: http://www.boomlings.com/database/downloadGJLevel22.php</span>
            </a-form-model-item>
            <a-form-model-item>
                <a-input v-model="form.levelID" type="text" placeholder="关卡ID"></a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-button type="primary" html-type="submit" :loading="request.loading" :disabled="
                !form.server || !form.levelID ||
                (form.server === 'custom' && !form.custom_server_url)">
                    搬运
                </a-button>
            </a-form-model-item>
        </a-form-model>
    </a-card>
</script>

<script>
    window.Vue.component('page', {
        template: `#page`,
        data: function () {
            return {
                form: {
                    server: 'official',
                    custom_server_url: null,

                    levelID: null
                },

                request: {
                    url: '{{ route('web.api.v1.tools.levels.reupload') }}',
                    data: this.form,
                    default_success_text: '上传成功!',
                    loading: false,
                }
            }
        },
        methods: {
            submit: function () {
                this.request.data = this.form;
                window.$request(this.request, function (response) {
                    app.$message.info('关卡ID: ' + response.data.id);
                });
            }
        }
    });
</script>
