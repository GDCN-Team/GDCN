<template>
    <page-layout class="lg:w-1/3" title="注册">
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
                    :feedback="form.errors.email ?? null"
                    :validation-status="form.errors.email ? 'error' : null"
                    label="邮箱"
                    required>
                    <n-input v-model:value="form.email" placeholder="邮箱" type="email"></n-input>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.password ?? null"
                    :validation-status="form.errors.password ? 'error' : null"
                    label="密码"
                    required>
                    <n-input v-model:value="form.password" placeholder="密码" type="password"></n-input>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.password_confirmation ?? null"
                    :validation-status="form.errors.password_confirmation ? 'error' : null"
                    label="密码确认"
                    required>
                    <n-input v-model:value="form.password_confirmation" placeholder="密码确认" type="password"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :disabled="form.processing || form.password !== form.password_confirmation"
                        :loading="form.processing"
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
