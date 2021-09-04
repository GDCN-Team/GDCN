<template>
    <page-layout class="lg:w-1/3" title="登录">
        <n-card>
            <n-form :model="form">
                <n-form-item
                    :feedback="form.errors.name ?? null"
                    :validation-status="form.errors.name ? 'error' : null"
                    label="用户名"
                    required>
                    <n-input v-model:value="form.name" placeholder="用户名"></n-input>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.password ?? null"
                    :validation-status="form.errors.password ? 'error' : null"
                    label="密码"
                    required>
                    <n-input v-model:value="form.password" placeholder="密码" type="password"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :disabled="form.processing"
                        :loading="form.processing"
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
            password: null,
            intended: require('querystring').parse(location.search)['?intended']
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
