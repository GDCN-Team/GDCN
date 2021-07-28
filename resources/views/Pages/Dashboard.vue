<template>
    <CommonLayout>
        <Row :gutter="10">
            <Col span="24">
                <Card class="text-black text-left" title="个人资料">
                    <Row :gutter="10">
                        <Col class="mb-5 lg:mb-0" span="24" :lg="12">
                            <small class="mb-5">账号信息:</small>
                            <div v-if="profile.account">
                                <p>账号ID: {{ profile.account.id }}</p>
                                <p>用户名: {{ profile.account.name }}</p>
                                <p>邮箱: {{ profile.account.email }}</p>
                                <p>注册时间: {{ new Date(profile.account.created_at).toLocaleString() }}</p>
                                <p>邮箱验证时间: {{
                                        profile.account.email_verified_at ? new Date(profile.account.email_verified_at).toLocaleString() : '无'
                                    }}
                                    <Button v-if="!profile.account.email_verified_at"
                                            :disabled="emailVerificationHasReSend" @click="reSendVerifyEmail"
                                            :loading="request1.loading"
                                            size="small" type="text">重发验证邮件
                                    </Button>
                                </p>
                            </div>
                            <h2 v-else>加载中</h2>
                        </Col>

                        <Col span="24" :lg="12">
                            <small class="mb-5">用户信息:</small>
                            <div v-if="profile.user">
                                <p>用户ID: {{ profile.user.id }}</p>
                                <p>用户名: {{ profile.user.name }}</p>
                                <p>用户唯一识别码: {{ profile.user.uuid }}</p>
                                <p @mouseleave="showUserUdid = false">
                                    设备唯一识别码: <span v-if="showUserUdid">{{ profile.user.udid }}</span><span
                                    class="text-red-600 cursor-pointer" @click="showUserUdid = true" v-else>点击查看</span>
                                </p>
                                <p>创建时间: {{ new Date(profile.user.created_at).toLocaleString() }}</p>
                            </div>
                            <h2 v-else>
                                <span v-if="profile.account">加载失败</span>
                                <span v-else>加载中</span>
                            </h2>
                        </Col>
                    </Row>

                    <div v-if="profile.account" class="mt-5">
                        <Button icon="ios-settings-outline" @click="showAccountSettings">账号设置</Button>
                        <Button :loading="request2.loading" icon="ios-log-out" @click="logout">登出</Button>
                    </div>
                </Card>
            </Col>
        </Row>
    </CommonLayout>
</template>

<script>
import CommonLayout from './CommonLayout';
import {createComponent, request} from '../../js/helper';

export default {
    name: "Dashboard",
    components: {
        CommonLayout
    },
    mounted: function () {
        this.updateProfile();
    },
    data: function () {
        return {
            showUserUdid: false,
            emailVerificationHasReSend: false,
            profile: {},
            request1: {
                loading: false
            },
            request2: {
                loading: false
            }
        }
    },
    methods: {
        updateProfile: function () {
            const that = this;
            request('GET', '/api/dashboard/player/profile', {
                onSuccess: function (response) {
                    that.profile = response.data;
                },
                onError: function (request) {
                    if (request.status === 401) {
                        const component = require('./Components/Login').default;
                        createComponent(component, {
                            callback: that.updateProfile
                        });
                    }
                }
            });
        },
        reSendVerifyEmail: function () {
            const that = this;
            that.emailVerificationHasReSend = true;

            request('GET', '/api/dashboard/player/email_verification/resend', {
                request: this.request1,
                onSuccess: function (response) {
                    that.$Message.success(response.msg);
                },
                onFailed: function () {
                    that.emailVerificationHasReSend = false;
                }
            });
        },
        logout: function () {
            const that = this;

            request('GET', '/api/auth/logout', {
                request: this.request2,
                onSuccess: function () {
                    Object.assign(that.$data, that.$options.data())
                    that.updateProfile();
                }
            })
        },
        showAccountSettings: function () {
            const that = this;
            const component = require('./Components/Dashboard/AccountSettings').default;
            createComponent(component, {
                account: that.profile.account,
                callback: that.updateProfile
            });
        }
    }
}
</script>

<style scoped>

</style>
