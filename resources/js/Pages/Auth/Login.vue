<template>
    <layout>
        <a-modal v-model="visible" :footer="null" title="登录" @cancel="$emit('back')">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <Input :errors="errors" :form="form" icon="user" name="name" placeholder="用户名" required></Input>
                <Input :errors="errors" :form="form" icon="lock" name="password" placeholder="密码" required
                       type="password">
                </Input>
                <div>
                    <CheckBox :form="form" name="remember">
                        记住我
                    </CheckBox>
                    <inertia-link
                        as="a-button"
                        class="float-right"
                        href="/auth/forget-password"
                        type="link">
                        忘记密码
                    </inertia-link>
                </div>
                <div>
                    <submit-bottom text="登录"></submit-bottom>
                    <inertia-link as="a-button" href="/auth/register" type="link">
                        注册新账号
                    </inertia-link>
                </div>
            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from '../Common/Layout/Web';
import CheckBox from "../Common/Form/CheckBox";
import Input from '../Common/Form/Input';
import SubmitBottom from '../Common/Form/SubmitBottom';

export default {
    name: "Login",
    components: {
        Layout,
        CheckBox,
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
                remember: false
            }
        }
    },
    methods: {
        submit: function () {
            return this.$inertia.form(this.form).post('/auth/login');
        }
    }
}
</script>

<style scoped>

</style>
