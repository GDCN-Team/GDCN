<x-layout>
    <page></page>
</x-layout>

<script id="page" type="text/x-template">
    <a-card title="歌曲上传 - 网易专版">
        <a-form-model :model="form" @submit="submit" @submit.native.prevent>
            <a-form-model-item>
                <a-input v-model="form.songID" type="number" placeholder="自定义歌曲ID"></a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-input v-model="form.musicID" type="number" placeholder="音乐ID"></a-input>
            </a-form-model-item>
            <a-form-model-item>
                <a-button type="primary" html-type="submit" :loading="request.loading" :disabled="!form.songID || !form.musicID">
                    上传
                </a-button>
            </a-form-model-item>
        </a-form-model>
    </a-card>
</script>

<script>
    window.Vue.component('page', {
        template: `#page`,
        data: function () {
            return {
                form: {
                    songID: '',
                    musicID: ''
                },

                request: {
                    url: '{{ route('web.api.v1.tools.songs.upload.netease') }}',
                    data: this.form,
                    loading: false,
                    default_success_text: '上传成功!'
                }
            }
        },
        methods: {
            submit: function () {
                this.request.data = this.form;
                window.$request(this.request);
            }
        }
    })
</script>
