<template>
    <layout :account="account" :friends="friends" :messages="messages" :user="user">
        <a-modal :footer="null" :visible="true" title="更改密码" @cancel="back">
            <a-form-model :model="form"
                          @submit="form.post('/dashboard/change-password')"
                          @submit.native.prevent>

                <!-- old password -->
                <a-form-model-item :help="errors.password"
                                   :validate-status="this.checkValidateStatus(errors.password, this.form)"
                                   has-feedback>
                    <a-input-password v-model="form.password" placeholder="原密码" required>
                        <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="lock"></a-icon>
                    </a-input-password>
                </a-form-model-item>

                <!-- new password -->
                <a-form-model-item :help="errors.new_password"
                                   :validate-status="this.checkValidateStatus(errors.new_password, this.form)"
                                   has-feedback>
                    <a-input-password v-model="form.new_password" placeholder="新密码" required>
                        <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="lock"></a-icon>
                    </a-input-password>
                </a-form-model-item>

                <a-form-model-item>
                    <a-button
                        :disabled="this.form.processing"
                        html-type="submit"
                        type="primary">
                        更改
                    </a-button>
                </a-form-model-item>
            </a-form-model>
        </a-modal>
    </layout>
</template>

<script>
import Layout from './Home';
import {back, checkValidateStatus} from "../../Helpers";

export default {
    name: "ChangePassword",
    layout: Layout,
    props: {
        errors: Object,
        account: Object,
        user: Object,
        friends: Array,
        messages: Array
    },
    data: function () {
        return {
            form: this.$inertia.form({
                new_password: null,
                password_confirmation: null
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
