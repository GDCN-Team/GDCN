<template>
    <Modal :closable="false" :mask-closable="false" v-model="show" title="账号链接">
        <Form :model="link">
            <FormItem prop="server">
                <Select placeholder="服务器" v-model="link.server">
                    <Option v-for="(name,symbol,key) in servers" :value="symbol" :key="key">{{ name }}</Option>
                </Select>
            </FormItem>
            <FormItem prop="name">
                <Input type="text" v-model="link.name" placeholder="用户名">
                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="password">
                <Input type="password" v-model="link.password" placeholder="密码">
                    <Icon type="ios-lock-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
        </Form>

        <template slot="footer">
            <Button type="primary" @click="cancel">取消</Button>
            <Button :loading="request.loading" type="primary" @click="linkAccount">链接</Button>
        </template>
    </Modal>
</template>

<script>
import {request} from "../../../../../js/helper";

export default {
    name: "Link",
    props: {
        callback: Function,
        remove: Function
    },
    mounted: function () {
        this.getServers();
    },
    data: function () {
        return {
            show: true,
            servers: {},
            link: {
                server: null,
                name: null,
                password: null
            },
            request: {
                loading: false
            }
        }
    },
    methods: {
        cancel: function () {
            this.remove();
        },
        getServers: function () {
            const that = this;

            request('GET', '/api/tools/servers', {
                onSuccess: function (response) {
                    that.servers = response.data;
                }
            });
        },
        linkAccount: function () {
            const that = this;

            request('POST', '/api/tools/account/link', {
                data: that.link,
                request: this.request,
                onSuccess: function(response) {
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
