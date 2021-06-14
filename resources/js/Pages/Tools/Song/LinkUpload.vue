<template>
    <layout>
        <a-modal :footer="null" :visible="true" title="歌曲上传 - 外链版" @cancel="back">
            <a-form-model :model="form"
                          @submit="form.post('/tools/song/upload:link')"
                          @submit.native.prevent>

                <!-- song ID -->
                <a-form-model-item :help="errors.song_id"
                                   :validate-status="this.checkValidateStatus(errors.song_id, this.form)"
                                   has-feedback>
                    <a-input v-model="form.song_id" placeholder="歌曲ID" required type="number"></a-input>
                    <a-button type="link" @click="autoSongID()">自动选取歌曲ID</a-button>
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

                <!-- link -->
                <a-form-model-item :help="errors.link"
                                   :validate-status="this.checkValidateStatus(errors.link, this.form)"
                                   has-feedback>
                    <a-input v-model="form.link" placeholder="歌曲外链" required type="url"></a-input>
                </a-form-model-item>

                <!-- submit -->
                <a-form-model-item>
                    <a-button
                        :disabled="this.form.processing"
                        html-type="submit"
                        type="primary">
                        上传
                    </a-button>
                </a-form-model-item>

            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from '../Home';
import {back, checkValidateStatus} from "../../../Helpers";

export default {
    name: "LinkUpload",
    props: {
        errors: Object,
        latestSongID: Number
    },
    components: {
        Layout
    },
    data() {
        return {
            form: this.$inertia.form({
                song_id: null,
                link: null,
                name: null,
                author_name: null
            })
        }
    },
    methods: {
        back,
        checkValidateStatus,
        autoSongID: function () {
            Inertia.reload({
                only: ['latestSongID']
            });

            this.form.song_id = this.latestSongID;
        }
    }
}
</script>

<style scoped>

</style>
