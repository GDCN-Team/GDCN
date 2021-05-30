<template>
    <layout>
        <a-modal v-model="visible" :footer="null" title="登录" @cancel="back">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <Input :errors="errors" :form="form" icon="user" name="name" placeholder="用户名"></Input>
                <Input :errors="errors" :form="form" icon="lock" name="password" placeholder="密码"
                       type="password">
                    <template slot="extra">
                        <a-row>
                            <a-col span="12">
                                <inertia-link as="a-button" href="/auth/register" type="link">注册新账号</inertia-link>
                            </a-col>
                            <a-col class="text-right" span="12">
                                <inertia-link as="a-button" href="/auth/forget-password" type="link">忘记密码</inertia-link>
                            </a-col>
                        </a-row>
                    </template>
                </Input>
                <submit-bottom :check="check" text="登录"></submit-bottom>
            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from '../Common/Layout/Web';
import Input from '../Common/Form/Input';
import SubmitBottom from '../Common/Form/SubmitBottom';

export default {
    name: "Login",
    components: {
        Layout,
        Input,
        SubmitBottom
    },
    props: {
        errors: Object
    },
    data: function () {
        return {
            visible: true,
            form: {
                name: '',
                password: ''
            }
        }
    },
    methods: {
        back: function () {
            window.history.go(-1);
        },
        check: function () {
            return this.form.name === '' || this.form.password === '';
        },
        submit: function () {
            return this.$inertia.form(this.form).post('/auth/login');
        }
    }
}
</script>

<style scoped>

</style>
