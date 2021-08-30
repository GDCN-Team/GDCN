<template>
    <page-layout class="lg:w-1/3" title="登录">
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
                    :validation-status="form.errors.password ? 'error' : null"
                    :feedback="form.errors.password ?? null"
                    path="form.password"
                    label="密码">
                    <n-input type="password" v-model:value="form.password" placeholder="密码"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="form.post(route('auth.login.api'));">
                        登录
                    </n-button>
                </n-form-item>
            </n-form>

            <template #action>
                <n-space justify="space-evenly">
                    <n-button text @click="redirectToRoute('auth.register')">注册新账号</n-button>
                    <n-button text @click="redirectToRoute('auth.name.forgot')">忘记用户名</n-button>
                    <n-button text @click="redirectToRoute('auth.password.forgot')">忘记密码</n-button>
                </n-space>
            </template>
        </n-card>
    </page-layout>
</template>

<script>
import {NButton, NCard, NForm, NFormItem, NInput, NSpace} from "naive-ui";
import PageLayout from "../Components/PageLayout";
import {useForm} from "@inertiajs/inertia-vue3";
import {redirectToRoute} from "../../../js/helper";

export default {
    name: "Login",
    setup: function () {
        const form = useForm({
            name: null,
            password: null
        });

        return {form, redirectToRoute}
    },
    components: {
        PageLayout,
        NCard,
        NForm,
        NFormItem,
        NInput,
        NButton,
        NSpace
    }
};
</script>
