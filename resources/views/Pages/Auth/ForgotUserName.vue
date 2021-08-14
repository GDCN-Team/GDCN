<template>
    <page-layout class="lg:w-1/3" title="忘记用户名">
        <n-card>
            <n-form :model="form">
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
                    <n-input type="password" v-model:value="form.password" placeholder="密码(忘记了请留空)"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="submit">
                        查找账号
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NButton, NCard, NForm, NFormItem, NInput, NTabPane, NTabs} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ForgotUserName",
    setup: function () {
        const form = useForm({
            email: null,
            password: null
        });

        const submit = function () {
            const api = $route('auth.name.forgot.api');
            form.post(api);
        }

        return {form, submit}
    },
    components: {
        PageLayout,
        NCard,
        NForm,
        NFormItem,
        NInput,
        NButton,
        NTabs,
        NTabPane
    }
}
</script>
