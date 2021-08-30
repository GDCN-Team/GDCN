<template>
    <page-layout class="lg:w-1/3" title="歌曲上传(外链版)">
        <n-card>
            <n-form :model="form">
                <n-form-item label="歌曲ID">
                    <n-input type="number" readonly :value="song.song_id.toString()" placeholder="歌曲ID"></n-input>
                </n-form-item>

                <n-form-item
                    required
                    :validation-status="form.errors.name ? 'error' : null"
                    :feedback="form.errors.name ?? null"
                    path="form.name"
                    label="歌曲名">
                    <n-input v-model:value="form.name" placeholder="歌曲名"></n-input>
                </n-form-item>

                <n-form-item
                    required
                    :validation-status="form.errors.author_name ? 'error' : null"
                    :feedback="form.errors.author_name ?? null"
                    path="form.author_name"
                    label="歌手名">
                    <n-input v-model:value="form.author_name" placeholder="歌手名"></n-input>
                </n-form-item>

                <n-form-item label="外链地址">
                    <n-input type="url" readonly :value="song.download_url" placeholder="外链地址"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing"
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
