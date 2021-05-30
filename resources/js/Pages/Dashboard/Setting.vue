<template>
    <layout :account="account" :friends="friends" :messages="messages" :user="user">
        <a-modal v-model="visible" :footer="null" title="账号设置" @cancel="back">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <Input :errors="errors" :form="form" icon="user" name="name" placeholder="用户名"></Input>
                <Input :errors="errors" :form="form" icon="mail" name="email" placeholder="邮箱"></Input>
                <Input :errors="errors" :form="form" icon="lock" name="password_confirmation" placeholder="密码确认"
                       type="password"></Input>
                <submit-bottom :check="check" text="更改"></submit-bottom>
            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from './Home';
import Input from '../Common/Form/Input';
import SubmitBottom from "../Common/Form/SubmitBottom";

export default {
    name: "Setting",
    components: {
        Layout,
        Input,
        SubmitBottom
    },
    props: {
        errors: Object,
        account: Object,
        user: Object,
        friends: Array,
        messages: Array
    },
    data: function () {
        return {
            visible: true,
            form: {
                name: this.account.name,
                email: this.account.email,
                password_confirmation: ''
            }
        }
    },
    methods: {
        back: function () {
            window.history.go(-1);
        },
        check: function () {
            return this.form.name === '' || this.form.email === '' || this.form.password_confirmation === '';
        },
        submit: function () {
            return this.$inertia.form(this.form).post('/dashboard/update-setting');
        }
    }
}
</script>

<style scoped>

</style>
