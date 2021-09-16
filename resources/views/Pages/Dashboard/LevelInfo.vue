<template>
    <page-layout :title="title" class="lg:w-2/3">
        <n-card>
            <n-descriptions :column="columns" bordered>
                <n-descriptions-item label="关卡ID">
                    {{ level.id }}
                </n-descriptions-item>
                <n-descriptions-item label="关卡名">
                    {{ level.name }}
                </n-descriptions-item>
                <n-descriptions-item label="关卡简介">
                    {{ level.desc }}
                </n-descriptions-item>
                <n-descriptions-item label="作者">
                    <n-button text type="primary"
                              @click="redirectToRoute('dashboard.account.info', level.user?.uuid)"
                              v-if="isAccount(level.user)">
                        {{ level.user?.name }}
                    </n-button>
                    <n-text v-else>
                        {{ level.user?.name }}
                    </n-text>
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
            <n-space vertical>
                <n-space v-for="comment in level.comments" justify="space-between">
                    <n-text>
                        <n-button text type="primary"
                                  @click="redirectToRoute('dashboard.account.info', comment.account?.id)">
                            {{ comment.account?.name }}
                        </n-button>
                        : {{ comment.content }}
                    </n-text>

                    <n-text>{{ formatTime(comment.created_at, '未知') }}</n-text>
                </n-space>
            </n-space>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {formatTime, isMobile, redirectToRoute} from "../../../js/helper";
import {NButton, NCard, NDescriptions, NDescriptionsItem, NSpace, NText} from "naive-ui";
import _ from "lodash";

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
        const isAccount = function (user) {
            return _.toInteger(user?.uuid) > 0;
        }

        return {formatTime, redirectToRoute, isAccount}
    },
    components: {
        PageLayout,
        NCard,
        NDescriptions,
        NDescriptionsItem,
        NButton,
        NText,
        NSpace
    }
}
</script>
