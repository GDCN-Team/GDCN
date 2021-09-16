<template>
    <page-layout :title="title" class="lg:w-2/3">
        <n-card>
            <n-descriptions :column="columns" bordered>
                <n-descriptions-item label="账号ID">
                    {{ account.id }}
                </n-descriptions-item>
                <n-descriptions-item label="用户名">
                    {{ account.name }}
                </n-descriptions-item>
                <n-descriptions-item label="邮箱验证状态">
                    {{ account.email_verfied_at === null ? '未验证' : '已验证' }}
                </n-descriptions-item>
                <n-descriptions-item label="星星">
                    {{ account?.user?.score?.stars ?? 0 }}
                </n-descriptions-item>
                <n-descriptions-item label="Creator Points">
                    {{ account?.user?.score?.creator_points ?? 0 }}
                </n-descriptions-item>
                <n-descriptions-item label="钻石">
                    {{ account?.user?.score?.diamonds ?? 0 }}
                </n-descriptions-item>
                <n-descriptions-item label="恶魔">
                    {{ account?.user?.score?.demons ?? 0 }}
                </n-descriptions-item>
                <n-descriptions-item label="Coins">
                    {{ account?.user?.score?.coins ?? 0 }}
                </n-descriptions-item>
                <n-descriptions-item label="User Coins">
                    {{ account?.user?.score?.user_coins ?? 0 }}
                </n-descriptions-item>
            </n-descriptions>
        </n-card>

        <n-card class="mt-2.5" title="评论">
            <n-space vertical>
                <n-space v-for="comment in account.comments" justify="space-between">
                    <n-text>{{ comment.content }}</n-text>
                    <n-text>{{ formatTime(comment.created_at, '未知') }}</n-text>
                </n-space>
            </n-space>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NCard, NDescriptions, NDescriptionsItem, NSpace, NText} from "naive-ui";
import {formatTime, isMobile} from "../../../js/helper";
import _ from "lodash";

export default {
    name: "AccountInfo",
    props: {
        account: Object
    },
    computed: {
        title: function () {
            return this.account.name + ' 的个人资料';
        },
        columns: function () {
            return isMobile() ? 3 : 1;
        }
    },
    setup: function () {
        return {formatTime}
    },
    components: {
        PageLayout,
        NCard,
        NDescriptions,
        NDescriptionsItem,
        NSpace,
        NText
    }
}
</script>
