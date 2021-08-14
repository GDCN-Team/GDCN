<template>
    <page-layout class="lg:w-1/3" title="歌曲上传(外链版)">
        <n-card>
            <n-form :model="form">
                <n-form-item
                    required
                    :validation-status="form.errors.song_id ? 'error' : null"
                    :feedback="form.errors.song_id ?? null"
                    path="form.song_id"
                    label="歌曲ID">
                    <n-input type="number" v-model:value="form.song_id" placeholder="歌曲ID"></n-input>
                    <n-button class="ml-2" @click="getLatestSongID">自动获取</n-button>
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

                <n-form-item
                    required
                    :validation-status="form.errors.link ? 'error' : null"
                    :feedback="form.errors.link ?? null"
                    path="form.link"
                    label="外链地址">
                    <n-input type="url" v-model:value="form.link" placeholder="外链地址"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="submit">
                        上传
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
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "UploadLink",
    props: {
        song_id: Number
    },
    setup: function (props) {
        const form = useForm({
            song_id: null,
            name: null,
            author_name: null,
            link: null
        });

        const submit = function () {
            const api = $route('tools.song.upload.link.api');
            form.post(api);
        }

        const getLatestSongID = function () {
            Inertia.reload({
                only: ['song_id'],
                onSuccess: function () {
                    form.song_id = props.song_id.toString();
                }
            });
        }

        return {form, submit, getLatestSongID}
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
