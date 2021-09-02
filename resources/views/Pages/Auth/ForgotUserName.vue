<template>
    <page-layout class="lg:w-1/3" title="忘记用户名">
        <n-card>
            <n-form :model="form">
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
                    <n-input v-model:value="form.password" placeholder="密码(忘记了请留空)" type="password"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :disabled="form.processing"
                        :loading="form.processing"
                        @click="form.post(route('auth.name.forgot.api'));">
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

        return {form}
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
