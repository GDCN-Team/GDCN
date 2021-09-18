<template>
    <page-layout title="歌曲上传(外链版)">
        <n-card>
            <n-form :model="form">
                <n-form-item
                    :feedback="form.errors.song_id ?? null"
                    :validation-status="form.errors.song_id ? 'error' : null"
                    label="歌曲ID"
                    required>
                    <n-input v-model:value="form.song_id" placeholder="歌曲ID" type="number"></n-input>
                    <n-button class="ml-2" @click="getLatestSongID">自动获取</n-button>
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

                <n-form-item
                    :feedback="form.errors.link ?? null"
                    :validation-status="form.errors.link ? 'error' : null"
                    label="外链地址"
                    required>
                    <n-input v-model:value="form.link" placeholder="外链地址" type="url"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :disabled="form.processing"
                        :loading="form.processing"
                        @click="form.post(route('tools.song.upload.link.api'))">
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

        const getLatestSongID = function () {
            Inertia.reload({
                only: ['song_id'],
                onSuccess: function () {
                    form.song_id = props.song_id.toString();
                }
            });
        }

        return {form, getLatestSongID}
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
