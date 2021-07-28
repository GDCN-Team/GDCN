<template>
    <CommonLayout>
        <Row type="flex" justify="center" :gutter="10">
            <Col span="24" :lg="8">
                <Card title="账号">
                    <Button @click="getTool('Account/Link', { show: 'Account/LinkList' })">账号链接</Button>
                    <Button @click="getTool('Account/LinkList')">账号链接列表</Button>
                </Card>
            </Col>

            <Col span="24" :lg="8">
                <Card class="card-media-mt" title="关卡">
                    <Button @click="getTool('Level/TransIn')">关卡搬进</Button>
                    <Button @click="getTool('Level/TransOut')">关卡搬出</Button>
                </Card>
            </Col>

            <Col span="24" :lg="16">
                <Card class="mt-2.5" title="歌曲">
                    <Button @click="getTool('Song/UploadNetease', { show: 'Song/List' })">歌曲上传(网易云专版)</Button>
                    <Button @click="getTool('Song/UploadLink', { show: 'Song/List' })">歌曲上传(外链版)</Button>
                    <Button @click="getTool('Song/List')">歌曲列表</Button>
                </Card>
            </Col>
        </Row>
    </CommonLayout>
</template>

<script>
import CommonLayout from "./CommonLayout";
import {createComponent, request} from "../../js/helper";

export default {
    name: "Tools",
    components: {
        CommonLayout
    },
    data: function () {
        return {
            tool: {},
            verified: false
        }
    },
    mounted: function () {
        this.checkAccount();
    },
    methods: {
        checkAccount: function () {
            const that = this;

            request('GET', '/api/auth/check/verified', {
                onSuccess: function () {
                    that.verified = true;
                },
                onError: function (request) {
                    if (request.status === 401) {
                        const component = require('./Components/Login').default;
                        createComponent(component, {
                            callback: that.checkAccount
                        });
                    }
                }
            })
        },
        getTool: function (name, options = {}) {
            const that = this;
            const props = {};

            if (options.show) {
                props.callback = function () {
                    that.getTool(options.show);
                }
            }

            if (this.verified) {
                const component = require(`./Components/Tools/${name}`).default;
                that.tool = createComponent(component, props);
            } else {
                that.$Modal.error({
                    content: '请不要试图绕过验证'
                });
            }
        }
    }
}
</script>

<style scoped>
.card-media-mt {
    @apply lg:mt-0 mt-2.5;
}
</style>
