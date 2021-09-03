<template>
    <page-layout :title="title" class="lg:w-1/3">
        <n-card>
            <n-descriptions :column="columns" bordered>
                <n-descriptions-item label="关卡ID">
                    {{ level.id }}
                </n-descriptions-item>
                <n-descriptions-item label="关卡名">
                    {{ level.name }}
                </n-descriptions-item>
                <n-descriptions-item label="关卡简介">
                    {{ Base64.decode(level.desc) }}
                </n-descriptions-item>
                <n-descriptions-item label="作者">
                    {{ level?.user?.name ?? '未知' }}
                </n-descriptions-item>
                <n-descriptions-item label="下载量">
                    {{ level.downloads }}
                </n-descriptions-item>
                <n-descriptions-item label="点赞量">
                    {{ level.likes }}
                </n-descriptions-item>
                <n-descriptions-item label="币的数量">
                    {{ level.coins }}
                </n-descriptions-item>
                <n-descriptions-item label="请求的星星">
                    {{ level.requested_stars }}
                </n-descriptions-item>
                <n-descriptions-item label="LDM">
                    {{ level.ldm ? '有' : '没有' }}
                </n-descriptions-item>
                <n-descriptions-item label="星星">
                    {{ level?.rating?.stars ?? 0 }}
                </n-descriptions-item>
                <n-descriptions-item label="Featured">
                    {{ level?.rating?.featured_score > 0 ? '是' : '否' }}
                </n-descriptions-item>
                <n-descriptions-item label="Epic">
                    {{ level?.rating?.epic ? '是' : '否' }}
                </n-descriptions-item>
                <n-descriptions-item label="银币">
                    {{ level?.rating?.coin_verified ? '是' : '否' }}
                </n-descriptions-item>
            </n-descriptions>
        </n-card>

        <n-card class="mt-2.5" title="评论">
            <n-list bordered>
                <n-list-item v-for="comment in level.comments">
                    <n-thing :description="'评论于 '+formatTime(comment.created_at, '未知')"
                             :title="comment.account.name + ': ' + Base64.decode(comment.content)">
                        <template #action>
                            <n-button @click="redirectToRoute('dashboard.account.info', comment.account.id)">
                                查看 {{ comment.account.name }} 的个人资料
                            </n-button>
                        </template>
                    </n-thing>
                </n-list-item>
            </n-list>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {formatTime, isMobile, redirectToRoute} from "../../../js/helper";
import {Base64} from "js-base64";
import {NButton, NCard, NDescriptions, NDescriptionsItem, NList, NListItem, NThing} from "naive-ui";

export default {
    name: "LevelInfo",
    props: {
        level: Object
    },
    computed: {
        title: function () {
            return `${this.level.name} - ${this.level?.user?.name ?? '未知'}`;
        },
        columns: function () {
            return isMobile() ? 3 : 1;
        }
    },
    setup: function () {
        return {Base64, formatTime, redirectToRoute}
    },
    components: {
        PageLayout,
        NCard,
        NDescriptions,
        NDescriptionsItem,
        NList,
        NListItem,
        NThing,
        NButton
    }
}
</script>
