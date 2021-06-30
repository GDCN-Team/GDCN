<template>
        <a-row :gutter="[10,10]">
            <a-col :md="12" span="24">
                <a-card title="账号链接">
                    <a-form-model :model="form" @submit="form.post('/tools/account/link')" @submit.native.prevent>
                        <a-form-model-item label="服务器">
                            <a-select v-model="form.server">
                                <a-select-option value="official">
                                    官服
                                </a-select-option>
                            </a-select>
                        </a-form-model-item>

                        <a-form-model-item :help="errors.target_name"
                                           :validate-status="this.checkValidateStatus(errors.target_name, this.form)"
                                           has-feedback>
                            <a-input v-model="form.target_name" placeholder="用户名" type="text">
                                <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="user"></a-icon>
                            </a-input>
                        </a-form-model-item>
                        <a-form-model-item :help="errors.target_password"
                                           :validate-status="this.checkValidateStatus(errors.target_password, this.form)"
                                           has-feedback>
                            <a-input v-model="form.target_password" placeholder="密码" type="password">
                                <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="lock"></a-icon>
                                </a-input>
                            </a-form-model-item>
                            <a-form-model-item>
                                <a-button
                                    :disabled="this.form.target_name === '' || this.form.target_password === '' || this.form.processing"
                                    html-type="submit"
                                    type="primary">
                                    链接
                                </a-button>
                            </a-form-model-item>
                    </a-form-model>
                </a-card>
            </a-col>
            <a-col :md="12" span="24">
                <a-card title="已链接账号">
                    <a-table :columns="columns" :data-source="links" rowKey="id">
                    <span slot="action" slot-scope="text, record">
                        <a-popconfirm
                            cancel-text="我手滑了"
                            ok-text="确定"
                            title="确定解绑?"
                            @confirm="$inertia.form({id: record.id}).post('/tools/account/unlink')">
                            <a-button>解除链接</a-button>
                        </a-popconfirm>
                    </span>
                    </a-table>
                </a-card>
            </a-col>
        </a-row>
</template>

<script>
import {checkValidateStatus} from '../../../Helpers';
import Layout from '../../Common/Layout';

export default {
    name: "Link",
    props: {
        errors: Object,
        links: Array
    },
    layout: Layout,
    data: function () {
        return {
            form: this.$inertia.form({
                server: 'official',
                target_name: null,
                target_password: null
            }),
            columns: [
                {
                    title: '用户名',
                    dataIndex: 'target_name'
                },
                {
                    title: '操作',
                    key: 'action',
                    scopedSlots: {customRender: 'action'},
                }
            ]
        }
    },
    methods: {
        checkValidateStatus
    }
}
</script>

<style scoped>

</style>
