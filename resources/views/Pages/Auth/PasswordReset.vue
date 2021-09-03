<template>
    <page-layout class="lg:w-2/3" title="重置密码">
        <n-card>
            <n-form :model="form">
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
                    label="确认密码"
                    required>
                    <n-input v-model:value="form.password_confirmation" placeholder="确认密码" type="password"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :disabled="form.processing || form.password !== form.password_confirmation"
                        :loading="form.processing"
                        @click="form.post(route('auth.password.reset.api'));">
                        重置
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NButton, NCard, NForm, NFormItem, NInput} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import querystring from 'querystring';

export default {
    name: "PasswordReset",
    setup: function () {
        const form = useForm({
            _: querystring.parse(window.location.search)['?_'],
            password: null,
            password_confirmation: null
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
