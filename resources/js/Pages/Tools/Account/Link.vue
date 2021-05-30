<template>
    <layout>
        <a-row :gutter="[10,10]">
            <a-col :md="12" span="24">
                <a-card title="账号链接">
                    <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                        <a-form-model-item label="服务器">
                            <a-select v-model="form.server">
                                <a-select-option value="www.boomlings.com/database">
                                    官服
                                </a-select-option>
                                <a-select-option value="dl.geometrydashchinese.com">
                                    官服(使用GDProxy代理)
                                </a-select-option>
                            </a-select>
                        </a-form-model-item>

                        <Input :errors="errors" :form="form" icon="user" name="target_name" placeholder="用户名"></Input>
                        <Input :errors="errors" :form="form" icon="lock" name="target_password" placeholder="密码"
                               type="password"></Input>
                        <submit-bottom :check="check" text="链接"></submit-bottom>
                    </a-form-model>
                </a-card>
            </a-col>
            <a-col :md="12" span="24">
                <a-card title="已链接账号">
                    <a-table :columns="columns" :data-source="links" rowKey="id">
                    <span slot="action" slot-scope="text, record">
                        <a-button @click="unlink(record.id)">解除链接</a-button>
                    </span>
                    </a-table>
                </a-card>
            </a-col>
        </a-row>
    </layout>
</template>

<script>
import Layout from '../../Common/Layout/Web';
import Input from '../../Common/Form/Input';
import SubmitBottom from '../../Common/Form/SubmitBottom';

export default {
    name: "Link",
    props: {
        errors: Object,
        links: Array
    },
    components: {
        Layout,
        Input,
        SubmitBottom
    },
    data: function () {
        return {
            visible: true,
            form: {
                server: 'dl.geometrydashchinese.com',
                target_name: '',
                target_password: ''
            },
            columns: [
                {
                    title: '服务器',
                    dataIndex: 'host'
                },
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
        check: function () {
            return this.form.target_name === '' || this.form.target_password === '';
        },
        submit: function () {
            return this.$inertia.form(this.form).post('/tools/account/link');
        },
        unlink: function (id) {
            return this.$inertia.form({id: id}).post('/tools/account/unlink');
        }
    }
}
</script>

<style scoped>

</style>
