<template>
    <layout>
        <a-row :gutter="[10, 10]">
            <a-col :md="12" span="24">
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
                    <Input :errors="errors" :form="form" name="password" placeholder="密码(你链接的账号密码)"
                           type="password"></Input>
                    <submit-bottom :check="check" text="搬运"></submit-bottom>
                </a-form-model>
            </a-col>
            <a-col :md="12" span="24">

            </a-col>
        </a-row>
    </layout>
</template>

<script>
import Layout from '../../Common/Layout/Web';
import Input from '../../Common/Form/Input';
import SubmitBottom from "../../Common/Form/SubmitBottom";

export default {
    name: "TransOut",
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
            this.$inertia.form(this.form).post('/tools/level/trans:out');
        }
    }
}
</script>

<style scoped>

</style>
