<template>
    <page-layout class="lg:w-1/3" title="歌曲上传(外链版)">
        <n-card>
            <n-form-item
                required
                :validation-status="form.errors.musicID ? 'error' : null"
                :feedback="form.errors.musicID ?? null"
                path="form.musicID"
                label="音乐ID">
                <n-input type="number" v-model:value="form.musicID" placeholder="音乐ID"></n-input>
                <n-button class="ml-2" @click="showDialogForParseLink">分享链接解析</n-button>
            </n-form-item>

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
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import PageLayout from "../../Components/PageLayout";
import {NButton, NCard, NForm, NFormItem, NInput} from "naive-ui";
import {h, ref} from "vue";

export default {
    name: "UploadNetease",
    props: {
        song_id: Number
    },
    setup: function (props) {
        const form = useForm({
            song_id: null,
            musicID: null
        });

        const showDialogForParseLink = function () {
            const link = ref(null);
            $dialog.info({
                title: '分享链接解析',
                positiveText: '解析',
                onPositiveClick: () => {
                    const querystring = require("querystring");
                    const musicID = querystring.parse(link.value.split('?')[1])['id'];
                    if (musicID) {
                        form.musicID = musicID;
                        $message.success('解析成功!');
                    } else {
                        $message.error('解析失败');
                    }
                },
                content: function () {
                    return h(NInput, {
                        placeholder: '输入分享链接',
                        value: link.value,
                        "onUpdate:value": function (value) {
                            link.value = value;
                        }
                    });
                }
            });
        }

        const submit = function () {
            const api = $route('tools.song.upload.netease.api');
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

        return {form, submit, getLatestSongID, showDialogForParseLink}
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
