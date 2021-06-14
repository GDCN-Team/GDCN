<template>
    <layout :account="account" :friends="friends" :messages="messages" :user="user">
        <a-modal :footer="null" :visible="true" title="个人资料" @cancel="back">
            <a-row :gutter="[10, 10]">
                <a-col :md="12" span="24">
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
                        <small>邮箱验证时间: {{ formatTime(account.email_verified_at) }}</small>
                    </a-row>
                </a-col>
                <a-col v-if="user" :md="12" span="24">
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
        </a-modal>
    </layout>
</template>

<script>
import Layout from './Home';
import {back, formatTime} from "../../Helpers";

export default {
    name: "Profile",
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
        formatTime,
        back
    }
}
</script>

<style scoped>

</style>
