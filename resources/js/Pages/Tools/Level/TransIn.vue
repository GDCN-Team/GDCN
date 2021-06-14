<template>
    <layout>
        <a-modal :footer="null" :visible="true" title="关卡搬进" @cancel="back">
            <a-form-model :model="form" @submit="form.post('/tools/level/trans:in')" @submit.native.prevent>
                <a-form-model-item label="服务器">
                    <a-select v-model="form.server">
                        <a-select-option value="official">
                            官服
                        </a-select-option>
                    </a-select>
                </a-form-model-item>

                <!-- level ID -->
                <a-form-model-item :help="errors.level_id"
                                   :validate-status="this.checkValidateStatus(errors.level_id, this.form)"
                                   has-feedback>
                    <a-input v-model="form.level_id" placeholder="关卡ID" required type="number"></a-input>
                </a-form-model-item>

                <!-- submit -->
                <a-form-model-item>
                    <a-button
                        :disabled="this.form.processing"
                        html-type="submit"
                        type="primary">
                        搬运
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
    name: "TransIn",
    layout: Layout,
    props: {
        errors: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                server: 'official',
                level_id: null
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
