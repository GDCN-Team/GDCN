<template>
    <Modal :closable="false" :mask-closable="false" v-model="show" title="登录" @on-ok="login">
        <Form :model="user">
            <FormItem prop="name">
                <Input type="text" v-model="user.name" placeholder="用户名">
                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="password">
                <Input type="password" v-model="user.password" placeholder="密码">
                    <Icon type="ios-lock-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
        </Form>

        <template slot="footer">
            <Button :loading="request.loading" type="primary" @click="login">登录</Button>
        </template>
    </Modal>
</template>

<script>
import {request} from "../../../js/helper";

export default {
    name: "Login",
    props: {
        callback: Function,
        remove: Function
    },
    data: function () {
        return {
            show: true,
            user: {
                name: null,
                password: null
            },
            request: {
                loading: false
            }
        }
    },
    methods: {
        login: function () {
            const that = this;

            request('POST', '/api/auth/login', {
                data: that.user,
                request: this.request,
                onSuccess: function (response) {
                    that.remove();

                    if (typeof that.callback === 'function') {
                        that.callback(response);
                    }
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
