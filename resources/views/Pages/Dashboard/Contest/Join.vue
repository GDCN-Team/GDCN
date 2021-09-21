<template>
    <page-layout title="参加比赛">
        <n-card>
            <n-space vertical>
                <n-h2 class="text-red-700">注意: 选择提交完后将无法修改!!!</n-h2>
                <n-table :bordered="false" :single-line="false">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>上传时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="level in levels">
                        <td>{{ level.id }}</td>
                        <td>{{ level.name }}</td>
                        <td>{{ formatTime(level.created_at, '未知') }}</td>
                        <td>
                            <n-button @click="redirectToRoute('dashboard.contest.join.api', [contest.id, level.id])">选择
                            </n-button>
                        </td>
                    </tr>
                    </tbody>
                </n-table>
            </n-space>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {NButton, NCard, NH2, NSpace, NTable} from "naive-ui";
import {formatTime, redirectToRoute} from "../../../../js/helper";

export default {
    name: "Join",
    props: {
        contest: Object,
        levels: Array
    },
    setup: function () {
        return {redirectToRoute, formatTime}
    },
    components: {PageLayout, NCard, NTable, NButton, NH2, NSpace}
}
</script>
