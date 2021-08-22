<template>
    <page-layout class="w-1/3" title="Newgrounds Proxy (NGProxy)">
        <n-card>
            <p class="text-lg font-bold">Newgrounds Proxy (NGProxy) 是 Geometry Dash Chinese (GDCN) 附属的一项服务,
                可以为您提供Newgrounds歌曲加速下载的服务</p>
            <br>
            <n-form :model="form">
                <n-form-item
                    :validation-status="form.errors.songID ? 'error' : null"
                    :feedback="form.errors.songID ?? null"
                    path="form.songID"
                    label="歌曲ID">
                    <n-input type="number" v-model:value="form.songID" placeholder="歌曲ID"></n-input>
                </n-form-item>

                <n-form-item>
                    <n-button
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="submit">
                        查询
                    </n-button>
                </n-form-item>
            </n-form>

            <n-descriptions v-if="song" :columns="columns" bordered title="查询结果">
                <n-descriptions-item label="歌曲ID">
                    {{ song.song_id }}
                </n-descriptions-item>
                <n-descriptions-item label="歌曲名">
                    {{ song.name }}
                </n-descriptions-item>
                <n-descriptions-item label="歌手ID">
                    {{ song.artist_id }}
                </n-descriptions-item>
                <n-descriptions-item label="歌手名">
                    {{ song.artist_name }}
                </n-descriptions-item>
                <n-descriptions-item label="大小">
                    {{ song.size }}MB
                </n-descriptions-item>
                <n-descriptions-item v-if="song.author_youtube_url" label="作者Youtube">
                    <n-button @click="redirect('https://youtube.com/channel/'+song.author_youtube_url)">
                        https://youtube.com/channel/{{ song.author_youtube_url }}
                    </n-button>
                </n-descriptions-item>
                <n-descriptions-item v-if="song.video_id" label="歌曲视频">
                    <n-button text @click="redirect('https://www.youtube.com/watch?v='+song.video_id)">
                        https://www.youtube.com/watch?v={{ song.video_id }}
                    </n-button>
                </n-descriptions-item>
                <n-descriptions-item label="禁用">
                    {{ song.disabled ? '是' : '否' }}
                </n-descriptions-item>
                <n-descriptions-item label="操作">
                    <n-button @click="redirect(decodeURIComponent(song.download_link))">试听</n-button>
                </n-descriptions-item>
            </n-descriptions>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NButton, NCard, NDescriptions, NDescriptionsItem, NForm, NFormItem, NInput} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import {isMobile, redirect} from "../../../js/helper";

export default {
    name: "Home",
    computed: {
        columns: function () {
            return isMobile() ? 3 : 1;
        }
    },
    props: {
        song: Object
    },
    setup: function () {
        const form = useForm({
            songID: null
        });

        const submit = function () {
            const api = $route('ngproxy.query.api');
            form.post(api);
        }

        return {form, submit, redirect}
    },
    components: {
        PageLayout,
        NCard,
        NForm,
        NFormItem,
        NInput,
        NButton,
        NDescriptions,
        NDescriptionsItem
    }
}
</script>
