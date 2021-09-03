<template>
    <page-layout class="lg:w-2/3" title="关卡搬进">
        <n-card>
            <n-form :model="form">
                <n-form-item
                    :feedback="form.errors.server ?? null"
                    :validation-status="form.errors.server ? 'error' : null"
                    label="服务器"
                    required>
                    <n-select v-model:value="form.server" :options="serverOptions" placeholder="服务器"></n-select>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.levelID ?? null"
                    :validation-status="form.errors.levelID ? 'error' : null"
                    label="关卡ID"
                    required>
                    <n-input v-model:value="form.levelID" placeholder="关卡ID" type="number"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :disabled="form.processing"
                        :loading="form.processing"
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
