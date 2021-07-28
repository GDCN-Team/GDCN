<template>
    <Modal :footer-hide="true" :mask-closable="false" v-model="show" title="账号链接列表">
        <Table :columns="columns" :data="links">
            <template slot-scope="{ row }" slot="server">
                <span>{{ servers[row.server] }}</span>
            </template>
            <template slot-scope="{ row }" slot="action">
                <Poptip
                    confirm
                    placement="left"
                    title="确认解绑?"
                    @on-ok="unlink(row.id)">
                    <Button type="error">解绑</Button>
                </Poptip>
            </template>
        </Table>
    </Modal>
</template>

<script>
import {request} from "../../../../../js/helper";

export default {
    name: "LinkList",
    data: function () {
        return {
            show: true,
            links: [],
            servers: [],
            columns: [
                {
                    title: 'ID',
                    key: 'id'
                },
                {
                    title: '服务器',
                    slot: 'server'
                },
                {
                    title: '用户名',
                    key: 'target_name'
                },
                {
                    title: '账号ID',
                    key: 'target_account_id'
                },
                {
                    title: '用户ID',
                    key: 'target_user_id'
                },
                {
                    title: '操作',
                    slot: 'action'
                }
            ]
        }
    },
    beforeMount: function () {
        this.getServers();
        this.updateLinks();
    },
    methods: {
        getServers: function () {
            const that = this;

            request('GET', '/api/tools/servers', {
                onSuccess: function (response) {
                    that.servers = response.data;
                }
            })
        },
        updateLinks: function () {
            const that = this;
            request('GET', '/api/tools/account/link/list', {
                onSuccess: function (response) {
                    that.links = response.data;
                }
            })
        },
        unlink: function (ID) {
            const that = this;
            request('DELETE', `/api/tools/account/unlink/${ID}`, {
                onSuccess: function () {
                    that.updateLinks();
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
