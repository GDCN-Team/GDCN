<template>
    <page-layout title="管理员工具">
        <n-grid cols="1 768:2" x-gap="10" y-gap="10">
            <n-grid-item>
                <n-card title="账号权限">
                    <n-space>
                        <n-button @click="redirectToRoute('admin.group.list')">权限组列表</n-button>
                    </n-space>
                </n-card>
            </n-grid-item>
            <n-grid-item>
                <n-card title="关卡">
                    <n-space>
                        <n-button @click="redirectToRoute('admin.level.pack.list')">关卡包列表</n-button>
                        <n-button @click="redirectToRoute('admin.level.gauntlet.list')">Gauntlet 列表</n-button>
                    </n-space>
                </n-card>
            </n-grid-item>
        </n-grid>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {NButton, NCard, NGrid, NGridItem, NSpace} from "naive-ui";
import {redirectToRoute} from "../../../js/helper";

export default {
    name: "Home",
    setup: function () {
        return {
            redirectToRoute
        }
    },
    components: {
        PageLayout,
        NCard,
        NGrid,
        NGridItem,
        NSpace,
        NButton
    }
}
</script>
