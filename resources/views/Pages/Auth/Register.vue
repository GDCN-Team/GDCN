<template>
    <page-layout class="lg:w-1/3" title="注册">
        <n-card>
            <n-form :model="form">
                <n-form-item
                    required
                    :validation-status="form.errors.name ? 'error' : null"
                    :feedback="form.errors.name ?? null"
                    path="form.name"
                    label="用户名">
                    <n-input v-model:value="form.name" placeholder="用户名"></n-input>
                </n-form-item>

                <n-form-item
                    required
                    :validation-status="form.errors.email ? 'error' : null"
                    :feedback="form.errors.email ?? null"
                    path="form.email"
                    label="邮箱">
                    <n-input type="email" v-model:value="form.email" placeholder="邮箱"></n-input>
                </n-form-item>

                <n-form-item
                    required
                    :validation-status="form.errors.password ? 'error' : null"
                    :feedback="form.errors.password ?? null"
                    path="form.password"
                    label="密码">
                    <n-input type="password" v-model:value="form.password" placeholder="密码"></n-input>
                </n-form-item>

                <n-form-item
                    required
                    :validation-status="form.errors.password_confirmation ? 'error' : null"
                    :feedback="form.errors.password_confirmation ?? null"
                    path="form.password_confirmation"
                    label="密码确认">
                    <n-input type="password" v-model:value="form.password_confirmation" placeholder="密码确认"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing || form.password !== form.password_confirmation"
                        @click="form.post(route('auth.register.api'))">
                        注册
                    </n-button>
                </n-form-item>
            </n-form>

            <template #action>
                <n-button text @click="redirectToRoute('auth.login')">已经有账号了? 去登录</n-button>
            </template>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NButton, NCard, NForm, NFormItem, NInput} from "naive-ui";
import {redirectToRoute} from "../../../js/helper";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "Register",
    setup: function () {
        const form = useForm({
            name: null,
            email: null,
            password: null,
            password_confirmation: null
        });

        return {redirectToRoute, form}
    },
    components: {
        PageLayout,
        NCard,
        NForm,
        NFormItem,
        NInput,
        NButton
    }
}
</script>
