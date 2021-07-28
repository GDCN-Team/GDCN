<template>
    <CommonLayout>
        <img src="https://cdn.geometrydashchinese.com/static/images/title.png" alt="Geometry Dash Chinese">

        <Alert class="mt-2" banner v-if="serverStat.notice"><span class="text-blue-500">公告:</span>
            {{ serverStat.notice.notice || '无' }}
        </Alert>

        <div class="mt-5 mb-10">
            <Button icon="logo-android" type="primary"
                    to="https://cdn.geometrydashchinese.com/download/GDCN.apk">
                <span>Android</span>
            </Button>
            <dropdown trigger="contextMenu">
                <Button to="https://cdn.geometrydashchinese.com/download/GDCN.zip" icon="logo-windows"
                        type="primary">
                    <span>Windows</span>
                </Button>
                <dropdown-menu slot="list">
                    <dropdown-item>
                        <a href="https://cdn.geometrydashchinese.com/download/GDCN.exe">无资源包版本</a>
                    </dropdown-item>
                </dropdown-menu>
            </dropdown>
            <Button disabled icon="logo-apple" type="primary"
                    to="https://cdn.geometrydashchinese.com/download/GDCN.ipa">
                <span>IOS</span>
            </Button>
        </div>

        <Card class="text-black mb-2.5" title="GDCN 介绍">
            GDCN 是由<a href="https://space.bilibili.com/24267334">渣渣120</a>制作的开源<a
            href="https://store.steampowered.com/app/322170">Geometry Dash(几何冲刺)</a>私服<br>
            开源地址: <a href="https://github.com/GDCN-Team/GDCN">https://github.com/GDCN-Team/GDCN</a>
            <br>私服仅供娱乐, 不能代替官服, 有能力的可以自行购买正版游玩
        </Card>

        <Alert class="mb-2.5" v-if="serverStat.count">{{ serverStat.count.account }}+ 账号, {{ serverStat.count.level }}+
            关卡,
            {{ serverStat.count.pack }}+ 关卡包, {{ serverStat.count.comment }}+ 评论, 等你来玩
        </Alert>

        <Row :gutter="10">
            <Col :lg="12" span="24">
                <Card title="赞助 & 支持" class="text-center">
                    <Button to="https://afdian.net/@WOSHIZHAZHA120" type="primary" icon="md-heart-outline">爱发电
                    </Button>
                    <Button to="https://qm.qq.com/cgi-bin/qm/qr?k=Bzo6uH4ExL3xnvK5tEm0mda5HpyA7kuK"
                            type="primary" icon="ios-people-outline">加入交流群
                    </Button>
                </Card>
            </Col>

            <Col class="lg:mt-0 mt-5" :lg="12" span="24">
                <Card title="在线工具" class="text-center">
                    <Button to="/dashboard" type="primary" icon="ios-pie-outline">Dashboard</Button>
                    <Button to="/tools" type="primary" icon="ios-build-outline">Tools</Button>
                </Card>
            </Col>
        </Row>
    </CommonLayout>
</template>

<script>
import {request} from '../../js/helper';
import CommonLayout from './CommonLayout';

export default {
    name: "Home",
    components: {
        CommonLayout
    },
    mounted: function () {
        this.updateServerStat();
    },
    data: function () {
        return {
            serverStat: {}
        };
    },
    methods: {
        updateServerStat: function () {
            const that = this;
            request('GET', '/api/server/stat', {
                onSuccess: function (response) {
                    that.loadNotifications();
                    that.serverStat = response.data;
                }
            });
        },
        loadNotifications: function() {
            _.map(this.serverStat.notifications, function(notification) {
                this.$Notice[notification.type]({
                    content: notification.content
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
