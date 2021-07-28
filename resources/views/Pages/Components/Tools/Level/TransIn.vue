<template>
    <Modal :closable="false" :mask-closable="false" v-model="show" title="关卡搬进">
        <Form :model="level">
            <FormItem prop="server">
                <Select placeholder="服务器" v-model="level.server">
                    <Option v-for="(name,symbol,key) in servers" :value="symbol" :key="key">{{ name }}</Option>
                </Select>
            </FormItem>
            <FormItem prop="id">
                <Input type="text" v-model="level.id" placeholder="关卡ID">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
        </Form>

        <template slot="footer">
            <Button type="primary" @click="cancel">取消</Button>
            <Button :loading="request.loading" type="primary" @click="transIn">搬运</Button>
        </template>
    </Modal>
</template>

<script>
import {request} from "../../../../../js/helper";

export default {
    name: "TransIn",
    props: {
        remove: Function,
        callback: Function
    },
    mounted: function () {
        this.getServers();
    },
    data: function () {
        return {
            show: true,
            servers: [],
            request: {
                loading: false
            },
            level: {
                server: null,
                id: null
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
        transIn: function () {
            const that = this;
            request('POST', '/api/tools/level/trans/in', {
                data: that.level,
                request: that.request,
                onSuccess: function (response) {
                    that.remove();

                    app.$Message.success({
                        content: response.msg
                    });

                    if (typeof that.callback === 'function') {
                        that.callback(response);
                    }
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
