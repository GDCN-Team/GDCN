<template>
    <page-layout class="lg:w-1/3" title="忘记密码">
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

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="form.post(route('auth.password.forgot.api'));">
                        发送重置邮件
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import {NButton, NCard, NForm, NFormItem, NInput} from "naive-ui";
import PageLayout from "../Components/PageLayout";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ForgotPassword",
    setup: function () {
        const form = useForm({
            name: null,
            email: null
        });

        return {form}
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
