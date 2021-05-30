<template>
    <layout>
        <a-modal v-model="visible" :footer="null" title="关卡搬进" @cancel="back">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <a-form-model-item label="服务器">
                    <a-select v-model="form.server">
                        <a-select-option value="www.boomlings.com/database">
                            官服
                        </a-select-option>
                        <a-select-option value="dl.geometrydashchinese.com">
                            官服(使用GDProxy代理)
                        </a-select-option>
                    </a-select>
                </a-form-model-item>

                <Input :errors="errors" :form="form" name="levelID" placeholder="关卡ID"></Input>
                <submit-bottom :check="check" text="搬运"></submit-bottom>
            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from '../Home';
import Input from '../../Common/Form/Input';
import SubmitBottom from "../../Common/Form/SubmitBottom";

export default {
    name: "TransIn",
    components: {
        Layout,
        Input,
        SubmitBottom
    },
    props: {
        errors: Object
    },
    data() {
        return {
            visible: true,
            form: {
                server: 'dl.geometrydashchinese.com',
                levelID: ''
            }
        }
    },
    methods: {
        back: function () {
            window.history.go(-1);
        },
        check: function () {
            return this.form.server === '' || this.form.levelID === '';
        },
        submit: function () {
            this.$inertia.form(this.form).post('/tools/level/trans:in');
        }
    }
}
</script>

<style scoped>

</style>
