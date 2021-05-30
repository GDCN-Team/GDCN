<template>
    <layout :account="account" :friends="friends" :messages="messages" :user="user">
        <a-modal v-model="visible" :footer="null" title="更改密码" @cancel="back">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <Input :errors="errors" :form="form" icon="lock" name="new_password" placeholder="新密码"
                       type="password"></Input>
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
    name: "ChangePassword",
    props: {
        errors: Object,
        account: Object,
        user: Object,
        friends: Array,
        messages: Array
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
                new_password: '',
                password_confirmation: ''
            }
        }
    },
    methods: {
        back: function () {
            window.history.go(-1);
        },
        check: function () {
            return this.form.new_password === '' || this.form.password_confirmation === '';
        },
        submit: function () {
            return this.$inertia.form(this.form).post('/dashboard/change-password');
        }
    }
}
</script>

<style scoped>

</style>
