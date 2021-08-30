<template>
    <page-layout class="lg:w-1/3" title="密码确认">
        <n-card>
            <n-form :model="form">
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
                        @click="form.post(route('auth.password.confirm.api'));">
                        确认
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import {NButton, NCard, NForm, NFormItem, NInput} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import PageLayout from "../Components/PageLayout";

export default {
    name: "PasswordConfirm",
    setup: function () {
        const form = useForm({
            password: null
        });

        return {form};
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
