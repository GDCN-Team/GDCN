<template>
    <Layout class="dark:bg-gray-800 h-screen dark:text-white">
        <Header>
            <Menu mode="horizontal" theme="dark" active-name="gdproxy">
                <menu-item to="https://dl.geometrydashchinese.com" name="gdproxy">
                    GDProxy
                </menu-item>
            </Menu>
        </Header>
        <Content class="text-center mt-5 overflow-auto">
            <h1 class="text-2xl">GDProxy</h1>
            <div class="mt-5">
                <Button icon="logo-android" type="primary"
                        to="https://cdn.geometrydashchinese.com/download/GDProxy.apk">
                    <span>Android</span>
                </Button>
                <dropdown trigger="contextMenu">
                    <Button icon="logo-windows" to="https://cdn.geometrydashchinese.com/download/GDProxy.zip"
                            type="primary">
                        <span>Windows</span>
                    </Button>
                    <dropdown-menu slot="list">
                        <dropdown-item>
                            <a href="https://cdn.geometrydashchinese.com/download/GDProxy.exe">无资源包版本</a>
                        </dropdown-item>
                    </dropdown-menu>
                </dropdown>
                <Button disabled icon="logo-apple" type="primary"
                        to="https://cdn.geometrydashchinese.com/download/GDProxy.ipa">
                    <span>IOS</span>
                </Button>
            </div>

            <div v-if="ngproxy_bind_account_getted" class="mt-5 lg:w-2/4 w-full text-center mx-auto">
                <Card title="NGProxy流量兑换">
                    <h2 class="mb-2 text-black">兑换至账号: {{ ngproxy_bind.account_name }}[{{
                            ngproxy_bind.account_id
                        }}]</h2>
                    <Input :disabled="request.loading" enter-button="兑换" placeholder="兑换码" search
                           @on-search="activeNGProxyCode"></Input>
                </Card>
            </div>

            <div class="mt-5 lg:w-2/4 w-full text-center mx-auto">
                <h6 class="mt-10 text-lg">流量公示</h6>
                <Table :columns="traffic_columns" :data="traffics.data"></Table>
                <Page :current="traffics.current_page"
                      @on-change="getTraffics"
                      class="mt-2"
                      :total="traffics.total"
                      :page-size="traffics.per_page"/>
            </div>
        </Content>
        <Footer class="dark:bg-gray-800 dark:text-white text-center px-0">
            <p class="text-sm">
                <a href="https://geometrydashchinese.com">几何冲刺玩家网</a>
                <a href="https://gf.geometrydashchinese.com">GDCN</a>
                <a href="https://ng.geometrydashchinese.com">NGProxy</a>
            </p>
            <p class="text-xs w-full">
                Copyright &copy; 2020 - {{ new Date().getFullYear() }} <a href="https://dl.geometrydashchinese.com">GDProxy</a>
                | <a
                href="https://beian.miit.gov.cn">吉ICP备18006293号</a>
            </p>
        </Footer>
    </Layout>
</template>

<script>
import {request} from "../../../../resources/js/helper";

export default {
    name: "home",
    data: function () {
        const that = this;

        return {
            traffic_columns: [
                {
                    title: '日期',
                    key: 'date',
                    align: 'center'
                },
                {
                    title: '流量',
                    key: 'count',
                    align: 'center',
                    render: function (h, params) {
                        return h('span', that.bytesToSize(params.row.count));
                    }
                }
            ],
            traffics: {
                current_page: 1,
                per_page: 7
            },
            request: {
                loading: false
            },
            ngproxy_bind_account_getted: false,
            ngproxy_bind: {
                account_id: null,
                account_name: null,
                ngproxy_user_id: null
            }
        }
    },
    mounted: function () {
        this.getTraffics(1);
        this.getBindedAccount();
    },
    methods: {
        getTraffics: function (page = 1) {
            const that = this;
            request('GET', `/api/traffics?page=${page}`, {
                onSuccess: function (response) {
                    that.traffics = response.data;
                }
            })
        },
        getBindedAccount: function () {
            const that = this;
            request('GET', '/api/get_ngproxy_binded_account', {
                request: that.request,
                onSuccess: function (response) {
                    that.ngproxy_bind_account_getted = true;
                    that.ngproxy_bind = response.data;
                },
                onFailed: function () {
                    that.ngproxy_bind_account_getted = false;
                },
                onError: function () {
                    that.ngproxy_bind_account_getted = false;
                }
            })
        },
        activeNGProxyCode: function (code) {
            request('POST', `https://ng.geometrydashchinese.com/api/activeCode/${this.ngproxy_bind.ngproxy_user_id}/${code}`, {
                onSuccess: function (response) {
                    app.$Message.success({
                        content: response.msg
                    });
                }
            });
        },
        bytesToSize: function (bytes) {
            if (bytes <= 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
        }
    }
}
</script>

<style scoped>

</style>
