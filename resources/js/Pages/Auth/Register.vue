<template>
    <layout>
        <a-modal v-model="visible" :footer="null" title="注册" @cancel="back">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <Input :errors="errors" :form="form" icon="user" name="name" placeholder="用户名"></Input>
                <Input :errors="errors" :form="form" icon="lock" name="password" placeholder="密码"
                       type="password"></Input>
                <Input :errors="errors" :form="form" icon="check" name="password_confirmation" placeholder="密码确认"
                       type="password"></Input>
                <Input :errors="errors" :form="form" icon="mail" name="email" placeholder="邮箱" type="email"></Input>
                <submit-bottom :check="check" text="注册"></submit-bottom>
            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from "../Common/Layout/Web";
import Input from "../Common/Form/Input";
import SubmitBottom from "../Common/Form/SubmitBottom";

export default {
    name: "Register",
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
                password: '',
                password_confirmation: '',
                email: ''
            }
        }
    },
    methods: {
        back: function () {
            window.history.go(-1);
        },
        check: function () {
            return this.form.name === '' || this.form.password === '' || this.form.email === '' || this.form.password !== this.form.password_confirmation;
        },
        submit: function () {
            this.$inertia.form(this.form).post('/auth/register');
        }
    }
}
</script>
