<template>
    <page-layout class="lg:w-1/3" title="账号链接">
        <n-card>
            <n-form :model="form">
                <n-form-item
                    required
                    :validation-status="form.errors.server ? 'error' : null"
                    :feedback="form.errors.server ?? null"
                    path="form.server"
                    label="服务器">
                    <n-select v-model:value="form.server" :options="serverOptions" placeholder="服务器"></n-select>
                </n-form-item>

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
                        @click="form.post(route('tools.account.link.api'))">
                        链接
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {useForm} from "@inertiajs/inertia-vue3";
import {NButton, NCard, NForm, NFormItem, NInput, NSelect, NSwitch} from "naive-ui";

export default {
    name: "Link",
    props: {
        servers: Object
    },
    setup: function (props) {
        const form = useForm({
            server: 'GDProxy',
            use_proxy: true,
            name: null,
            password: null
        });

        const serverOptions = _.map(props.servers, function (server, name) {
            return {
                label: server.alias,
                value: name
            };
        });

        return {form, serverOptions}
    },
    components: {
        PageLayout,
        NCard,
        NForm,
        NFormItem,
        NInput,
        NSelect,
        NSwitch,
        NButton
    }
}
</script>
