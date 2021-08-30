<template>
    <page-layout class="lg:w-1/3" title="关卡搬进">
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
                    :validation-status="form.errors.levelID ? 'error' : null"
                    :feedback="form.errors.levelID ?? null"
                    path="form.levelID"
                    label="关卡ID">
                    <n-input type="number" v-model:value="form.levelID" placeholder="关卡ID"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="form.post(route('tools.level.trans.in.api'))">
                        搬运
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {NButton, NCard, NForm, NFormItem, NInput, NSelect} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import _ from "lodash";

export default {
    name: "TransIn",
    props: {
        servers: Object
    },
    setup: function (props) {
        const serverOptions = _.map(props.servers, function (server, name) {
            return {
                label: server.alias,
                value: name
            };
        });

        const form = useForm({
            server: 'GDProxy',
            levelID: null
        });

        return {serverOptions, form}
    },
    components: {
        PageLayout,
        NCard,
        NForm,
        NFormItem,
        NSelect,
        NInput,
        NButton
    }
}
</script>
