<template>
    <page-layout title="Dashboard">
        <n-card title="统计">
            <n-space justify="space-evenly">
                <n-statistic label="账号">{{ dynamic['accounts_count'] }}</n-statistic>
                <n-statistic label="关卡">{{ dynamic['levels_count'] }}</n-statistic>
                <n-statistic label="关卡包">{{ dynamic['level_packs_count'] }}</n-statistic>
                <n-statistic label="评论">{{ dynamic['comments_count'] }}</n-statistic>
                <n-statistic label="Moderator">{{ dynamic['moderators_count'] }}</n-statistic>
            </n-space>
        </n-card>
        <n-grid class="mt-5" cols="1 768:3" x-gap="10" y-gap="10">
            <n-grid-item>
                <n-card title="服务器动态">
                    <n-tabs justify-content="space-evenly" type="line">
                        <n-tab-pane name="new_accounts" tab="新增账号">
                            <n-space v-for="account in dynamic['new_accounts'].data" justify="space-between">
                                <n-button text type="primary"
                                          @click="redirectToRoute('dashboard.account.info', account.id)">
                                    {{ account.id }} - {{ account.name }}
                                </n-button>
                                <n-text>注册于 {{ formatTime(account.created_at, '未知') }}</n-text>
                            </n-space>
                        </n-tab-pane>
                        <n-tab-pane name="new_levels" tab="新增关卡">
                            <n-space vertical>
                                <n-space v-for="level in dynamic['new_levels'].data" justify="space-between">
                                    <div>
                                        <n-button text type="primary"
                                                  @click="redirectToRoute('dashboard.level.info', level.id)">
                                            {{ level.id }} - {{ level.name }}&nbsp;
                                        </n-button>
                                        <br>
                                        <n-button v-if="isAccount(level.user)" text type="info"
                                                  @click="redirectToRoute('dashboard.account.info', level.user?.uuid)">
                                            By
                                            {{ level.user?.name }}
                                        </n-button>
                                        <n-button @click="showNotAccountMessage" v-else text type="error">By
                                            {{ level.user?.name }}
                                        </n-button>
                                    </div>
                                    <n-text>上传于 {{ formatTime(level.created_at, '未知') }}</n-text>
                                </n-space>
                            </n-space>
                        </n-tab-pane>
                    </n-tabs>
                </n-card>
            </n-grid-item>
            <n-grid-item>
                <n-card title="Rate 动态">
                    <n-tabs justify-content="space-evenly" type="line">
                        <n-tab-pane name="new_rated_levels" tab="最近">
                            <n-space vertical>
                                <n-space v-for="rating in dynamic['new_rated_levels'].data" justify="space-between">
                                    <div>
                                        <n-button text type="primary"
                                                  @click="redirectToRoute('dashboard.level.info', rating?.level?.id)">
                                            {{ rating?.level?.id }} - {{ rating?.level?.name }}&nbsp;
                                        </n-button>
                                        <br>
                                        <n-button v-if="isAccount(rating?.level?.user)" text type="info"
                                                  @click="redirectToRoute('dashboard.account.info', rating?.level?.user?.uuid)">
                                            By
                                            {{ rating?.level?.user?.name }}
                                        </n-button>
                                        <n-button
                                            @click="showNotAccountMessage"
                                            v-else text type="error">By {{ rating?.level?.user?.name }}
                                        </n-button>
                                    </div>
                                    <n-text>Rate于 {{ formatTime(rating.created_at, '未知') }}</n-text>
                                </n-space>
                            </n-space>
                        </n-tab-pane>
                        <n-tab-pane name="new_rated_featured_levels" tab="Featured">
                            <n-space vertical>
                                <n-space v-for="rating in dynamic['new_rated_featured_levels'].data"
                                         justify="space-between">
                                    <div>
                                        <n-button text type="primary"
                                                  @click="redirectToRoute('dashboard.level.info', rating?.level?.id)">
                                            {{ rating?.level?.id }} - {{ rating?.level?.name }}&nbsp;
                                        </n-button>
                                        <br>
                                        <n-button v-if="isAccount(rating?.level?.user)" text type="info"
                                                  @click="redirectToRoute('dashboard.account.info', rating?.level?.user?.uuid)">
                                            By
                                            {{ rating?.level?.user?.name }}
                                        </n-button>
                                        <n-button
                                            @click="showNotAccountMessage"
                                            v-else text type="error">By {{ rating?.level?.user?.name }}
                                        </n-button>
                                    </div>
                                    <n-text>Rate于 {{ formatTime(rating.created_at, '未知') }}</n-text>
                                </n-space>
                            </n-space>
                        </n-tab-pane>
                        <n-tab-pane name="new_rated_epic_levels" tab="Epic">
                            <n-space vertical>
                                <n-space v-for="rating in dynamic['new_rated_epic_levels'].data"
                                         justify="space-between">
                                    <div>
                                        <n-button text type="primary"
                                                  @click="redirectToRoute('dashboard.level.info', rating?.level?.id)">
                                            {{ rating?.level?.id }} - {{ rating?.level?.name }}&nbsp;
                                        </n-button>
                                        <br>
                                        <n-button v-if="isAccount(rating?.level?.user)" text type="info"
                                                  @click="redirectToRoute('dashboard.account.info', rating?.level?.user?.uuid)">
                                            By
                                            {{ rating?.level?.user?.name }}
                                        </n-button>
                                        <n-button
                                            @click="showNotAccountMessage"
                                            v-else text type="error">By {{ rating?.level?.user?.name }}
                                        </n-button>
                                    </div>
                                    <n-text>Rate于 {{ formatTime(rating.created_at, '未知') }}</n-text>
                                </n-space>
                            </n-space>
                        </n-tab-pane>
                    </n-tabs>
                </n-card>
            </n-grid-item>
            <n-grid-item>
                <n-card title="排行榜">
                    <n-tabs justify-content="space-evenly" type="line">
                        <n-tab-pane name="top_stars" tab="追星狂">
                            <n-space v-for="(score, index) in dynamic['top_stars'].data" justify="space-between">
                                <n-text>
                                    <n-button v-if="isAccount(score.user)"
                                              @click="redirectToRoute('dashboard.account.info', score.user?.uuid)" text
                                              type="primary">
                                        {{ score.user?.id }} - {{ score.user?.name }}
                                    </n-button>
                                    <n-button @click="showNotAccountMessage" text type="error" v-else>
                                        {{ score.user?.id }} - {{ score.user?.name }}
                                    </n-button>
                                    | {{ score.stars }} Stars
                                </n-text>
                                <n-text>TOP {{ index + 1 }}</n-text>
                            </n-space>
                        </n-tab-pane>
                        <n-tab-pane name="top_diamonds" tab="钻石!">
                            <n-space v-for="(score, index) in dynamic['top_diamonds'].data" justify="space-between">
                                <n-text>
                                    <n-button v-if="isAccount(score.user)"
                                              @click="redirectToRoute('dashboard.account.info', score.user?.uuid)" text
                                              type="primary">
                                        {{ score.user?.id }} - {{ score.user?.name }}
                                    </n-button>
                                    <n-button @click="showNotAccountMessage" text type="error" v-else>
                                        {{ score.user?.id }} - {{ score.user?.name }}
                                    </n-button>
                                    | {{ score.diamonds }} Diamonds
                                </n-text>
                                <n-text>TOP {{ index + 1 }}</n-text>
                            </n-space>
                        </n-tab-pane>
                        <n-tab-pane name="top_demons" tab="恶魔猎手">
                            <n-space v-for="(score, index) in dynamic['top_demons'].data" justify="space-between">
                                <n-text>
                                    <n-button v-if="isAccount(score.user)"
                                              @click="redirectToRoute('dashboard.account.info', score.user?.uuid)" text
                                              type="primary">
                                        {{ score.user?.id }} - {{ score.user?.name }}
                                    </n-button>
                                    <n-button @click="showNotAccountMessage" text type="error" v-else>
                                        {{ score.user?.id }} - {{ score.user?.name }}
                                    </n-button>
                                    | {{ score.demons }} Demons
                                </n-text>
                                <n-text>TOP {{ index + 1 }}</n-text>
                            </n-space>
                        </n-tab-pane>
                        <n-tab-pane name="top_creator_points" tab="关卡制作者">
                            <n-space v-for="(score, index) in dynamic['top_creator_points'].data"
                                     justify="space-between">
                                <n-text>
                                    <n-button v-if="isAccount(score.user)"
                                              @click="redirectToRoute('dashboard.account.info', score.user?.uuid)" text
                                              type="primary">
                                        {{ score.user?.id }} - {{ score.user?.name }}
                                    </n-button>
                                    <n-button @click="showNotAccountMessage" text type="error" v-else>
                                        {{ score.user?.id }} - {{ score.user?.name }}
                                    </n-button>
                                    | {{ score.creator_points }} Creator Points
                                </n-text>
                                <n-text>TOP {{ index + 1 }}</n-text>
                            </n-space>
                        </n-tab-pane>
                    </n-tabs>
                </n-card>
            </n-grid-item>
        </n-grid>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NButton, NCard, NDivider, NGrid, NGridItem, NSpace, NStatistic, NTabPane, NTabs, NText} from "naive-ui";
import {formatTime, redirectToRoute} from "../../../js/helper";
import _ from "lodash";

export default {
    name: "Home",
    props: {
        dynamic: Object
    },
    setup: function () {
        const isAccount = function (user) {
            if (!user) {
                return false;
            }

            return _.toInteger(user?.uuid) > 0;
        }

        const showNotAccountMessage = function () {
            $message.error('非注册用户无法展示个人资料');
        }

        return {formatTime, redirectToRoute, isAccount, showNotAccountMessage}
    },
    components: {
        PageLayout,
        NGrid,
        NGridItem,
        NCard,
        NTabs,
        NTabPane,
        NButton,
        NStatistic,
        NSpace,
        NText,
        NDivider
    }
}
</script>
