<template>
    <page-layout title="比赛详情">
        <n-space vertical>
            <n-card>
                <n-descriptions :column="columns" bordered>
                    <n-descriptions-item label="ID">
                        {{ contest.id }}
                    </n-descriptions-item>
                    <n-descriptions-item label="名称">
                        {{ contest.name }}
                    </n-descriptions-item>
                    <n-descriptions-item label="介绍">
                        {{ contest.desc }}
                    </n-descriptions-item>
                    <n-descriptions-item label="举办方">
                        <n-button text type="primary"
                                  @click="redirectToRoute('dashboard.account.info', contest.account.id)">
                            {{ contest.account.name }}
                        </n-button>
                    </n-descriptions-item>
                    <n-descriptions-item label="截止时间">
                        {{ formatTime(contest.expired_at, '长期有效') }}
                    </n-descriptions-item>
                    <n-descriptions-item label="创建时间">
                        {{ formatTime(contest.created_at, '未知') }}
                    </n-descriptions-item>
                </n-descriptions>
            </n-card>

            <n-card title="参赛作品">
                <n-space vertical>
                    <n-button @click="redirectToRoute('dashboard.contest.join', contest.id)">参加这场比赛</n-button>

                    <n-data-table
                        :columns="levelTable.columns"
                        :data="levels.data"
                        :loading="levelTable.updatePageForm.processing"
                        :pagination="levelTable.pagination"
                        remote
                        @update:page="levelTable.updatePage"
                    ></n-data-table>
                </n-space>
            </n-card>
        </n-space>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {NButton, NCard, NDataTable, NDescriptions, NDescriptionsItem, NSpace, NText} from "naive-ui";
import {formatTime, isMobile, redirectToRoute} from "../../../../js/helper";
import {useForm} from "@inertiajs/inertia-vue3";
import {h} from "vue";

export default {
    name: "Info",
    props: {
        contest: Object,
        levels: Object
    },
    computed: {
        columns: function () {
            return isMobile() ? 3 : 1;
        }
    },
    setup: function (props) {
        const levelTable = {
            columns: [
                {
                    title: '参赛ID',
                    key: 'id'
                },
                {
                    title: '关卡',
                    key: 'level',
                    render: function (row) {
                        let extra = '';
                        _.map(row.level.flags, function (flag) {
                            if (flag === 'DISQUALIFIED') {
                                extra += '(失去资格)';
                            }
                        });

                        return h(NButton, {
                            text: true,
                            type: 'primary',
                            onClick: () => redirectToRoute('dashboard.level.info', row.level.id)
                        }, {
                            default: () => row.level.id + ' - ' + row.level.name + ' By ' + row.level.user.name + ' ' + extra
                        })
                    }
                },
                {
                    title: '参赛时间',
                    key: 'created_at',
                    render: function (row) {
                        return h(NText, null, {
                            default: () => formatTime(row.created_at)
                        })
                    }
                }
            ],
            updatePageForm: useForm(null),
            updatePage: function (page) {
                this.updatePageForm.get($route('dashboard.contest.info', {_query: {page}}));
            },
            pagination: {
                page: props.levels.current_page,
                pageSize: props.levels.per_page,
                itemCount: props.levels.total
            }
        }

        return {redirectToRoute, formatTime, levelTable}
    },
    components: {PageLayout, NCard, NButton, NDescriptions, NDescriptionsItem, NSpace, NDataTable}
}
</script>
