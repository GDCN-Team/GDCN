<template>
    <layout :account="account" :friends="friends" :messages="messages" :user="user">
        <a-modal :footer="null" :visible="true" title="账号设置" @cancel="back">
            <a-form-model :model="form"
                          @submit="form.post('/dashboard/update-setting')"
                          @submit.native.prevent>

                <!-- name -->
                <a-form-model-item :help="errors.name"
                                   :validate-status="this.checkValidateStatus(errors.name, this.form)"
                                   has-feedback>
                    <a-input v-model="form.name" placeholder="用户名" required type="text">
                        <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="user"></a-icon>
                    </a-input>
                </a-form-model-item>

                <!-- email -->
                <a-form-model-item :help="errors.email"
                                   :validate-status="this.checkValidateStatus(errors.email, this.form)"
                                   has-feedback>
                    <a-input v-model="form.email" placeholder="邮箱" required type="email">
                        <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="mail"></a-icon>
                    </a-input>
                </a-form-model-item>

                <!-- password -->
                <a-form-model-item :help="errors.password"
                                   :validate-status="this.checkValidateStatus(errors.password, this.form)"
                                   has-feedback>
                    <a-input-password v-model="form.password" placeholder="密码" required type="password">
                        <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="lock"></a-icon>
                    </a-input-password>
                </a-form-model-item>

                <!-- password confirmation -->
                <a-form-model-item :help="errors.password"
                                   :validate-status="this.checkValidateStatus(errors.password, this.form)"
                                   has-feedback>
                    <a-input-password v-model="form.password" placeholder="密码确认" required>
                        <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="lock"></a-icon>
                    </a-input-password>
                </a-form-model-item>

                <!-- submit -->
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
import Layout from '../Home';
import {back, checkValidateStatus} from "../../../Helpers";

export default {
    name: "Setting",
    components: {
        Layout
    },
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
                name: this.account.name,
                email: this.account.email,
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
