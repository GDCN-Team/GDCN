<x-layout>
    <page></page>
</x-layout>

<script id="page" type="text/x-template">
    <a-row :gutter="[10,10]">
        <a-col :md="12" span="24">
            <a-card title="账号链接">
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
                        <span>示例地址（官服）: http://www.boomlings.com/database/accounts/loginGJAccount.php</span>
                    </a-form-model-item>
                    <a-form-model-item>
                        <a-input v-model="form.remote_name" type="text" placeholder="用户名"></a-input>
                    </a-form-model-item>
                    <a-form-model-item>
                        <a-input v-model="form.remote_password" type="password" placeholder="密码"></a-input>
                    </a-form-model-item>
                    <a-form-model-item>
                        <a-button type="primary" html-type="submit" :loading="request.loading" :disabled="
                !form.server || !form.remote_name || !form.remote_password ||
                (form.server === 'custom' && !form.custom_server_url)">
                            链接
                        </a-button>
                    </a-form-model-item>
                </a-form-model>
            </a-card>
        </a-col>
        <a-col :md="12" span="24">
            <a-card title="链接历史">
                <a-list item-layout="horizontal" :data-source="links">
                    <a-list-item slot="renderItem" slot-scope="item, index">
                        <button @click="unlink(item.id)" slot="actions">删除</button>
                        <a-list-item-meta :description="'服务器: ' + (item.host === 'dl.geometrydashchinese.com' ? '官服' : item.host)+', 链接时间: '+(new Date(item.created_at).toLocaleString())">
                            <span slot="title">@{{ item.target_name }}<br></span>
                        </a-list-item-meta>
                    </a-list-item>
                </a-list>
            </a-card>
        </a-col>
    </a-row>
</script>

<script>
    window.Vue.component('page', {
        template: `#page`,
        mounted: function () {
            this.getLinkedAccounts();
        },
        data: function () {
            return {
                form: {
                    server: 'official',
                    custom_server_url: null,

                    remote_name: null,
                    remote_password: null
                },

                request: {
                    url: '{{ route('web.api.v1.tools.accounts.link') }}',
                    data: this.form,
                    default_success_text: '链接成功!',
                    loading: false,
                },
                links: []
            }
        },
        methods: {
            getLinkedAccounts: function() {
                const that = this;
                window.$request({
                    url: '{{ route('web.api.v1.tools.accounts.link.list') }}',
                    default_success_text: '链接历史获取成功!'
                }, function(response) {
                    that.links = response.data;
                });
            },
            unlink: function(id) {
                const that = this;
                window.$request({
                    url: '{{ route('web.api.v1.tools.accounts.link.unlink') }}',
                    data: {
                        linkID: id
                    },
                    default_success_text: '删除成功!'
                }, function(response) {
                    that.getLinkedAccounts();
                });
            },
            submit: function () {
                const that = this;
                this.request.data = this.form;
                window.$request(this.request, function() {
                    that.getLinkedAccounts();
                });
            }
        }
    });
</script>
