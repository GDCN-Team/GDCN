<template>
    <page-layout title="歌曲上传(外链版)">
        <n-card>
            <n-form :model="form">
                <n-form-item label="歌曲ID">
                    <n-input :value="song.song_id.toString()" placeholder="歌曲ID" readonly type="number"></n-input>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.name ?? null"
                    :validation-status="form.errors.name ? 'error' : null"
                    label="歌曲名"
                    required>
                    <n-input v-model:value="form.name" placeholder="歌曲名"></n-input>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.author_name ?? null"
                    :validation-status="form.errors.author_name ? 'error' : null"
                    label="歌手名"
                    required>
                    <n-input v-model:value="form.author_name" placeholder="歌手名"></n-input>
                </n-form-item>

                <n-form-item label="外链地址">
                    <n-input :value="song.download_url" placeholder="外链地址" readonly type="url"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :disabled="form.processing"
                        :loading="form.processing"
                        @click="form.post(route('tools.song.edit.api'))">
                        修改
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {NButton, NCard, NForm, NFormItem, NInput} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "Edit",
    props: {
        song: Object
    },
    setup: function (props) {
        const form = useForm({
            id: props.song.id,
            name: props.song.name,
            author_name: props.song.author_name
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
