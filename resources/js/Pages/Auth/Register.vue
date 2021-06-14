<template>
    <a-modal :footer="null" :visible="true" title="注册" @cancel="back">
        <a-form-model
            :model="form"
            @submit="form.post('/auth/register')"
            @submit.native.prevent>

            <!-- name -->
            <a-form-model-item :help="errors.name"
                               :validate-status="this.checkValidateStatus(errors.name, this.form)"
                               has-feedback>
                <a-input v-model="form.name" placeholder="用户名" required type="text">
                    <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="user"></a-icon>
                </a-input>
            </a-form-model-item>

            <!-- password -->
            <a-form-model-item :help="errors.password"
                               :validate-status="this.checkValidateStatus(errors.password, this.form)"
                               has-feedback>
                <a-input-password v-model="form.password" placeholder="密码" required>
                    <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="lock"></a-icon>
                </a-input-password>
            </a-form-model-item>

            <!-- password confirmation -->
            <a-form-model-item :help="errors.password_confirmation"
                               :validate-status="this.checkValidateStatus(errors.password_confirmation, this.form)"
                               has-feedback>
                <a-input-password v-model="form.password_confirmation" placeholder="密码确认" required>
                    <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="lock"></a-icon>
                </a-input-password>
            </a-form-model-item>

            <!-- email -->
            <a-form-model-item :help="errors.email"
                               :validate-status="this.checkValidateStatus(errors.email, this.form)"
                               has-feedback>
                <a-input v-model="form.email" placeholder="邮箱" required type="email">
                    <a-icon slot="prefix" style="color:rgba(0,0,0,.25)" type="mail"></a-icon>
                </a-input>
            </a-form-model-item>

            <!-- submit -->
            <a-form-model-item>
                <a-button
                    :disabled="this.form.processing"
                    html-type="submit"
                    type="primary">
                    注册
                </a-button>
            </a-form-model-item>

        </a-form-model>
    </a-modal>
</template>

<script>
import {back, checkValidateStatus} from "../../Helpers";
import Layout from "../Common/Layout";

export default {
    name: "Register",
    layout: Layout,
    props: {
        errors: Object
    },
    data: function () {
        return {
            form: this.$inertia.form({
                name: null,
                password: null,
                password_confirmation: null,
                email: null
            })
        }
    },
    methods: {
        back,
        checkValidateStatus,
    }
}
</script>
