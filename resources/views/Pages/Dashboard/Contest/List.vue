<template>
    <page-layout title="所有比赛">
        <n-card>
            <n-data-table
                :columns="columns"
                :data="contests.data"
                :loading="updatePageForm.processing"
                :pagination="pagination"
                remote
                @update:page="updatePage"
            ></n-data-table>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {NButton, NCard, NDataTable, NText} from "naive-ui";
import {formatTime, redirectToRoute} from "../../../../js/helper";
import {h} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "List",
    props: {
        contests: Object
    },
    setup: function (props) {
        const columns = [
            {
                title: 'ID',
                key: 'id'
            },
            {
                title: '名称',
                key: 'name'
            },
            {
                title: '介绍',
                key: 'desc'
            },
            {
                title: '举办方',
                key: 'account.name',
                render: function (row) {
                    return h(NButton, {
                        text: true,
                        type: 'primary',
                        onClick: () => redirectToRoute('dashboard.account.info', row.account.id)
                    }, {
                        default: () => row.account.name
                    });
                }
            },
            {
                title: '截止时间',
                key: 'expired_at',
                render: function (row) {
                    return h(NText, null, {
                        default: () => formatTime(row.expired_at, '长期有效')
                    })
                }
            },
            {
                title: '操作',
                key: 'action',
                render: function (row) {
                    return h(NButton, {
                        onClick: () => redirectToRoute('dashboard.contest.info', row.id)
                    }, {
                        default: () => '查看'
                    })
                }
            }
        ];

        const updatePageForm = useForm(null);
        const updatePage = function (page) {
            updatePageForm.get($route('dashboard.contest.list', {_query: {page}}));
        }

        const pagination = {
            page: props.contests.current_page,
            pageSize: props.contests.per_page,
            itemCount: props.contests.total
        }

        return {columns, updatePageForm, pagination, updatePage}
    },
    components: {PageLayout, NCard, NDataTable}
}
</script>
