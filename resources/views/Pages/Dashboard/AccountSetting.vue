<template>
    <page-layout class="lg:w-1/3" title="账号设置">
        <n-card>
            <n-form :model="form">
                <n-form-item
                    :validation-status="form.errors.name ? 'error' : null"
                    :feedback="form.errors.name ?? null"
                    path="form.name"
                    label="新用户名">
                    <n-input v-model:value="form.name" placeholder="新用户名(留空不变)"></n-input>
                </n-form-item>

                <n-form-item
                    :validation-status="form.errors.email ? 'error' : null"
                    :feedback="form.errors.email ?? null"
                    path="form.email"
                    label="新邮箱">
                    <n-input type="email" v-model:value="form.email" placeholder="新邮箱(留空不变)"></n-input>
                </n-form-item>

                <n-form-item
                    :validation-status="form.errors.password ? 'error' : null"
                    :feedback="form.errors.password ?? null"
                    path="form.password"
                    label="新密码">
                    <n-input type="password" v-model:value="form.password" placeholder="新密码(留空不变)"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="submit">
                        修改
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NButton, NCard, NForm, NFormItem, NInput, NTabPane, NTabs} from "naive-ui";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {computed} from "vue";

export default {
    name: "AccountSetting",
    setup: function () {
        const form = useForm({
            name: null,
            email: null,
            password: null
        });

        const submit = function () {
            const api = $route('dashboard.profile.setting.api');
            form.post(api);
        }

        return {form, submit}
    },
    components: {
        PageLayout,
        NCard,
        NTabs,
        NTabPane,
        NForm,
        NFormItem,
        NInput,
        NButton
    }
}
</script>
