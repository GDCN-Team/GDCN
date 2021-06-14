<template>
    <layout>
        <a-row :gutter="[10, 10]">
            <a-col span="24">
                <a-card :title="'欢迎!' + account.name">
                    <a-row :gutter="[10, 10]">
                        <a-col :md="12" span="24">
                            <inertia-link as="a-button" href="/dashboard/profile">个人资料</inertia-link>
                            <inertia-link as="a-button" href="/dashboard/setting">账号设置</inertia-link>
                            <inertia-link as="a-button" href="/dashboard/change-password">更改密码</inertia-link>
                            <inertia-link as="a-button" href="/auth/logout">登出</inertia-link>
                        </a-col>
                        <a-col :md="6" span="24">
                            <a-row :gutter="[10, 10]">
                                <strong>账号信息</strong>
                                <hr>
                                账号ID: {{ account.id }}
                                <br>
                                用户名: {{ account.name }}
                                <br>
                                邮箱: {{ account.email }}
                                <br>
                                注册时间: {{ formatTime(account.created_at) }}
                                <br>
                                邮箱验证时间: {{ formatTime(account.email_verified_at) }}
                            </a-row>
                        </a-col>
                        <a-col v-if="user" :md="6" span="24">
                            <a-row :gutter="[10, 10]">
                                <strong>用户信息:</strong>
                                <hr>
                                用户ID: {{ user.id }}
                                <br>
                                用户名: {{ user.name }}
                                <br>
                                用户唯一标识码: {{ user.uuid }}
                                <br>
                                设备唯一标识码: <span
                                class="bg-black p-px hover:text-white">{{ user.udid }}</span>
                                <br>
                                创建时间: {{ formatTime(user.created_at) }}
                            </a-row>
                        </a-col>
                    </a-row>
                </a-card>
            </a-col>
            <a-col :md="12" span="24">
                <a-card title="好友">
                    <a-list v-for="friend in friends" v-bind:key="friend.id" item-layout="horizontal">
                        <a-list-item>
                            {{ friend.name }}
                        </a-list-item>
                    </a-list>
                </a-card>
            </a-col>
            <a-col :md="12" span="24">
                <a-card title="私信">
                    <a-list v-for="message in messages" v-bind:key="message.id" item-layout="horizontal">
                        <a-list-item>
                            {{ message.subject }} - {{ message.sender.id }}
                        </a-list-item>
                    </a-list>
                </a-card>
            </a-col>
        </a-row>

        <slot></slot>
    </layout>
</template>

<script>
import Layout from '../Common/Layout';
import {formatTime} from "../../Helpers";

export default {
    name: "Home",
    props: {
        account: Object,
        user: Object,
        friends: Array,
        messages: Array
    },
    components: {
        Layout
    },
    methods: {
        formatTime
    }
}
</script>

<style scoped>

</style>
