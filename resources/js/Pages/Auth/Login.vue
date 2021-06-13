<template>
    <layout>
        <a-modal :visible="true" title="登录" @cancel="$emit('back')">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <a-form-model-item :help="errors.name"
                                   :validate-status="this.checkValidateStatus(errors.name, this.form)"
                                   has-feedback>
                    <a-input v-model="form.name" placeholder="用户名" type="text">
                        <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="user"></a-icon>
                    </a-input>
                </a-form-model-item>
                <a-form-model-item :help="errors.password"
                                   :validate-status="this.checkValidateStatus(errors.password, this.form)"
                                   has-feedback>
                    <a-input v-model="form.password" placeholder="密码" type="password">
                        <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="lock"></a-icon>
                    </a-input>
                </a-form-model-item>
                <a-form-model-item>
                    <a-checkbox v-model="form.remember">
                        记住我
                    </a-checkbox>
                </a-form-model-item>
                <a-form-model-item>
                    <a-button
                        :disabled="this.form.name === '' || this.form.password === '' || this.form.processing"
                        html-type="submit"
                        type="primary">
                        登录
                    </a-button>
                </a-form-model-item>
            </a-form-model>
            <template slot="footer">
                <a-row :gutter="[10, 10]">
                    <a-col span="12">
                        <inertia-link as="a-button" class="float-left" href="/auth/register" type="link">
                            注册新账号
                        </inertia-link>
                    </a-col>
                    <a-col span="12">
                        <inertia-link as="a-button" class="float-right" href="/auth/forget-password" type="link">
                            忘记密码
                        </inertia-link>
                    </a-col>
                </a-row>
            </template>
        </a-modal>
    </layout>
</template>

<script>
import Layout from '../Common/Layout';
import {checkValidateStatus} from '../../Helpers';

export default {
    name: "Login",
    components: {
        Layout
    },
    props: {
        errors: Object
    },
    data: function () {
        return {
            form: {
                name: '',
                password: '',
                remember: false
            }
        }
    },
    methods: {
        checkValidateStatus,
        submit: function () {
            return this.$inertia.form(this.form).post('/auth/login');
        }
    }
}
</script>

<style scoped>

</style>
