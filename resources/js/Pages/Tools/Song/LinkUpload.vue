<template>
    <layout>
        <a-modal v-model="visible" :footer="null" title="歌曲上传 - 外链版" @cancel="back">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <Input :errors="errors" :form="form" name="song_id" placeholder="自定义歌曲ID">
                    <template slot="extra">
                        <a-button type="link" @click="autoSongID()">自动选取歌曲ID</a-button>
                    </template>
                </Input>
                <Input :errors="errors" :form="form" name="name" placeholder="歌曲名"></Input>
                <Input :errors="errors" :form="form" name="author_name" placeholder="歌手名"></Input>
                <Input :errors="errors" :form="form" icon="download" name="link" placeholder="链接" type="url"></Input>
                <submit-bottom :check="check" text="上传"></submit-bottom>
            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from '../Home';
import Input from '../../Common/Form/Input';
import SubmitBottom from "../../Common/Form/SubmitBottom";

export default {
    name: "LinkUpload",
    props: {
        errors: Object,
        latestSongID: Number
    },
    components: {
        Layout,
        Input,
        SubmitBottom
    },
    data() {
        return {
            visible: true,
            form: {
                song_id: '',
                link: '',
                name: '',
                author_name: ''
            }
        }
    },
    methods: {
        back: function () {
            window.history.go(-1);
        },
        check: function () {
            return this.form.link === '' || this.form.name === '' || this.form.author_name === '';
        },
        submit: function () {
            this.$inertia.form(this.form).post('/tools/song/upload:link');
        },
        autoSongID: function () {
            window.Inertia.reload({
                only: ['latestSongID']
            });

            this.form.song_id = this.latestSongID;
        }
    }
}
</script>

<style scoped>

</style>
