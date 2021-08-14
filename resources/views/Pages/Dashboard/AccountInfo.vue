<template>
    <page-layout class="lg:w-1/3" :title="title">
        <n-card>
            <n-descriptions bordered :column="columns">
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
            <n-list bordered>
                <n-list-item v-for="comment in account.comments">
                    <n-thing :title="Base64.decode(comment.content)"
                             :description="'评论于 '+formatTime(comment.created_at, '未知')"></n-thing>
                </n-list-item>
            </n-list>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NCard, NDescriptions, NDescriptionsItem, NList, NListItem, NThing} from "naive-ui";
import {formatTime, isMobile} from "../../../js/helper";
import {Base64} from "js-base64";

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
        return {Base64, formatTime}
    },
    components: {
        PageLayout,
        NCard,
        NDescriptions,
        NDescriptionsItem,
        NList,
        NListItem,
        NThing
    }
}
</script>
