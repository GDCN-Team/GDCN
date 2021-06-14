<template>
    <a-modal :visible="true" title="登录" @cancel="back">
        <a-form-model :model="form"
                      @submit="form.post('/auth/login')"
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

            <a-row :gutter="[10, 10]">
                <!-- remember -->
                <a-col span="12">
                    <a-form-model-item>
                        <a-checkbox v-model="form.remember">
                            记住我
                        </a-checkbox>
                    </a-form-model-item>
                </a-col>

                <!-- submit -->
                <a-col span="12">
                    <a-form-model-item>
                        <a-button
                            :disabled="this.form.processing"
                            class="float-right"
                            html-type="submit"
                            type="primary">
                            登录
                        </a-button>
                    </a-form-model-item>
                </a-col>
            </a-row>

        </a-form-model>

            <template slot="footer">
                <a-row :gutter="[10, 10]">
                    <a-col span="12">
                        <inertia-link as="a-button" class="float-left" href="/auth/register" type="link">
                            注册新账号
                        </inertia-link>
                    </a-col>
                    <a-col span="12">
                        <inertia-link as="a-button" class="float-right" href="/auth/forget-password" type="link">
                            忘记密码
                        </inertia-link>
                    </a-col>
                </a-row>
            </template>

        </a-modal>
</template>

<script>
import Layout from '../Common/Layout';
import {back, checkValidateStatus} from '../../Helpers';

export default {
    name: "Login",
    layout: Layout,
    props: {
        errors: Object
    },
    data: function () {
        return {
            form: this.$inertia.form({
                name: null,
                password: null,
                remember: false
            })
        }
    },
    methods: {
        back,
        checkValidateStatus
    }
}
</script>
