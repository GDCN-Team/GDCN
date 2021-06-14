<template>
    <layout :songs="songs">
        <a-modal :footer="null" :visible="true" title="歌曲编辑" @cancel="back">
            <a-form-model :model="form" @submit="form.post(`/tools/song/${this.form.id}/edit`)" @submit.native.prevent>
                <!-- song ID -->
                <a-form-model-item :help="errors.song_id"
                                   :validate-status="this.checkValidateStatus(errors.song_id, this.form)"
                                   has-feedback>
                    <a-input v-model="form.song_id" placeholder="歌曲ID" required type="number"></a-input>
                </a-form-model-item>

                <!-- name -->
                <a-form-model-item :help="errors.name"
                                   :validate-status="this.checkValidateStatus(errors.name, this.form)"
                                   has-feedback>
                    <a-input v-model="form.name" placeholder="歌曲名" required type="text"></a-input>
                </a-form-model-item>

                <!-- author name -->
                <a-form-model-item :help="errors.author_name"
                                   :validate-status="this.checkValidateStatus(errors.author_name, this.form)"
                                   has-feedback>
                    <a-input v-model="form.author_name" placeholder="歌手名" required type="text"></a-input>
                </a-form-model-item>

                <!-- submit -->
                <a-form-model-item>
                    <a-button
                        :disabled="this.form.processing"
                        html-type="submit"
                        type="primary">
                        提交
                    </a-button>
                </a-form-model-item>

            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from './List';
import {back, checkValidateStatus} from "../../../Helpers";

export default {
    name: "Edit",
    layout: Layout,
    props: {
        song: Object,
        songs: Array,
        errors: Object
    },
    data() {
        return {
            visible: true,
            form: this.$inertia.form({
                id: this.song.id,
                song_id: this.song.song_id,
                name: this.song.name,
                author_name: this.song.author_name
            })
        }
    },
    methods: {
        back,
        checkValidateStatus
    }
}
</script>

<style scoped>

</style>
