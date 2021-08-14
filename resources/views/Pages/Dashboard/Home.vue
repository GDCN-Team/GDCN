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
        <n-grid class="mt-5" x-gap="10" y-gap="10" cols="1 768:3">
            <n-grid-item>
                <n-card title="服务器动态">
                    <n-tabs justify-content="space-evenly" type="line">
                        <n-tab-pane name="new_accounts" tab="新增账号">
                            <n-list bordered>
                                <n-list-item v-for="account in dynamic['new_accounts'].data">
                                    <template #suffix>
                                        <n-button @click="redirectToRoute('dashboard.account.info', account.id)">
                                            个人资料
                                        </n-button>
                                    </template>

                                    <n-thing :title="account.name"
                                             :description="'注册于 '+formatTime(account.created_at, '未知')"></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                        <n-tab-pane name="new_levels" tab="新增关卡">
                            <n-list bordered>
                                <n-list-item v-for="level in dynamic['new_levels'].data">
                                    <template #suffix>
                                        <n-button @click="redirectToRoute('dashboard.level.info', level.id)">
                                            查看
                                        </n-button>
                                    </template>

                                    <n-thing :title="level.name + ' - ' + (level?.creator?.name ?? '未知')"
                                             :description="'上传于 '+formatTime(level.created_at, '未知')"></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                    </n-tabs>
                </n-card>
            </n-grid-item>
            <n-grid-item>
                <n-card title="Rate 动态">
                    <n-tabs justify-content="space-evenly" type="line">
                        <n-tab-pane name="new_rated_levels" tab="最近">
                            <n-list bordered>
                                <n-list-item v-for="rating in dynamic['new_rated_levels'].data">
                                    <template #suffix>
                                        <n-button @click="redirectToRoute('dashboard.level.info', rating.level.id)">
                                            查看
                                        </n-button>
                                    </template>

                                    <n-thing
                                        :title="(rating?.level?.id ?? '未知') + ' - ' + (rating?.level?.name ?? '未知') + ' - ' + (rating?.level?.creator?.name ?? '未知')"
                                        :description="'Rate于 '+formatTime(rating.created_at, '未知')"></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                        <n-tab-pane name="new_rated_featured_levels" tab="Featured">
                            <n-list bordered>
                                <n-list-item v-for="rating in dynamic['new_rated_featured_levels'].data">
                                    <template #suffix>
                                        <n-button @click="redirectToRoute('dashboard.level.info', rating.level.id)">
                                            查看
                                        </n-button>
                                    </template>

                                    <n-thing
                                        :title="(rating?.level?.id ?? '未知') + ' - ' + (rating?.level?.name ?? '未知') + ' - ' + (rating?.level?.creator?.name ?? '未知')"
                                        :description="'Rate于 '+formatTime(rating.created_at, '未知')"></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                        <n-tab-pane name="new_rated_epic_levels" tab="Epic">
                            <n-list bordered>
                                <n-list-item v-for="rating in dynamic['new_rated_epic_levels'].data">
                                    <template #suffix>
                                        <n-button @click="redirectToRoute('dashboard.level.info', rating.level.id)">
                                            查看
                                        </n-button>
                                    </template>

                                    <n-thing
                                        :title="(rating?.level?.id ?? '未知') + ' - ' + (rating?.level?.name ?? '未知') + ' - ' + (rating?.level?.creator?.name ?? '未知')"
                                        :description="'Rate于 '+formatTime(rating.created_at, '未知')"></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                    </n-tabs>
                </n-card>
            </n-grid-item>
            <n-grid-item>
                <n-card title="排行榜">
                    <n-tabs justify-content="space-evenly" type="line">
                        <n-tab-pane name="top_stars" tab="追星狂">
                            <n-list bordered>
                                <n-list-item v-for="(score, index) in dynamic['top_stars'].data">
                                    <n-thing
                                        :title="(score.owner?.name ?? '未知') + ' - ' + score.stars"
                                        :description="'TOP ' + (index + 1)"
                                    ></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                        <n-tab-pane name="top_diamonds" tab="钻石!">
                            <n-list bordered>
                                <n-list-item v-for="(score, index) in dynamic['top_diamonds'].data">
                                    <n-thing
                                        :title="(score.owner?.name ?? '未知') + ' - ' + score.diamonds"
                                        :description="'TOP ' + (index + 1)"
                                    ></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                        <n-tab-pane name="top_demons" tab="恶魔猎手">
                            <n-list bordered>
                                <n-list-item v-for="(score, index) in dynamic['top_demons'].data">
                                    <n-thing
                                        :title="(score.owner?.name ?? '未知') + ' - ' + score.demons"
                                        :description="'TOP ' + (index + 1)"
                                    ></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                        <n-tab-pane name="top_creator_points" tab="关卡制作者">
                            <n-list bordered>
                                <n-list-item v-for="(score, index) in dynamic['top_creator_points'].data">
                                    <n-thing
                                        :title="(score.owner?.name ?? '未知') + ' - ' + score.creator_points"
                                        :description="'TOP ' + (index + 1)"
                                    ></n-thing>
                                </n-list-item>
                            </n-list>
                        </n-tab-pane>
                    </n-tabs>
                </n-card>
            </n-grid-item>
        </n-grid>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {
    NButton,
    NCard,
    NGrid,
    NGridItem,
    NList,
    NListItem,
    NSpace,
    NStatistic,
    NTabPane,
    NTabs,
    NThing
} from "naive-ui";
import {formatTime, redirectToRoute} from "../../../js/helper";

export default {
    name: "Home",
    props: {
        dynamic: Object
    },
    setup: function () {
        return {formatTime, redirectToRoute}
    },
    components: {
        PageLayout,
        NGrid,
        NGridItem,
        NCard,
        NTabs,
        NTabPane,
        NList,
        NListItem,
        NButton,
        NThing,
        NStatistic,
        NSpace
    }
}
</script>
