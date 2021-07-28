<template>
    <Modal :closable="false" :mask-closable="false" v-model="show" title="账号设置" @on-ok="update">
        <Form :model="setting">
            <FormItem prop="name">
                <Input type="text" v-model="setting.name" placeholder="用户名">
                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="email">
                <Input type="email" v-model="setting.email" placeholder="邮箱">
                    <Icon type="ios-mail-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="password">
                <Input type="password" v-model="setting.password" placeholder="密码(留空不变)">
                    <Icon type="ios-lock-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="password_confirmation">
                <Input type="password" v-model="setting.password_confirmation" placeholder="密码确认">
                    <Icon type="ios-checkmark" slot="prepend"></Icon>
                </Input>
            </FormItem>
        </Form>

        <template slot="footer">
            <Button type="primary" @click="cancel">取消</Button>
            <Button :loading="request.loading" type="primary" @click="update">更改</Button>
        </template>
    </Modal>
</template>

<script>
import {request} from "../../../../js/helper";

export default {
    name: "AccountSettings",
    props: {
        callback: Function,
        account: Object,
        remove: Function
    },
    data: function () {
        return {
            show: true,
            request: {
                loading: false
            },
            setting: {
                ...this.account,
                password_confirmation: null
            }
        }
    },
    methods: {
        cancel: function () {
            this.remove();
        },
        update: function () {
            const that = this;

            request('POST', '/api/dashboard/player/settings/update', {
                data: that.setting,
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
