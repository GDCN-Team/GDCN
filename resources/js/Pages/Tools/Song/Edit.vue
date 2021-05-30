<template>
    <layout :songs="songs">
        <a-modal v-model="visible" :footer="null" title="歌曲编辑" @cancel="back">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <Input :errors="errors" :form="form" name="song_id" placeholder="自定义歌曲ID" type="number"></Input>
                <Input :errors="errors" :form="form" name="name" placeholder="歌曲名"></Input>
                <Input :errors="errors" :form="form" name="author_name" placeholder="歌手名"></Input>
                <submit-bottom :check="check" text="提交"></submit-bottom>
            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from './List';
import Input from '../../Common/Form/Input';
import SubmitBottom from "../../Common/Form/SubmitBottom";

export default {
    name: "Edit",
    props: {
        song: Object,
        songs: Array,
        errors: Object
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
                id: this.song.id,
                song_id: this.song.song_id,
                name: this.song.name,
                author_name: this.song.author_name
            }
        }
    },
    methods: {
        back: function () {
            window.history.go(-1);
        },
        check: function () {
            return this.form.song_id === '' || this.form.name === '' || this.form.author_name === '';
        },
        submit: function () {
            this.$inertia.form(this.form).post('/tools/song/edit:save');
        }
    }
}
</script>

<style scoped>

</style>
