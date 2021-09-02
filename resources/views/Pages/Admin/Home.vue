<template>
    <page-layout class="lg:w-2/3" title="管理员工具">
        <n-grid cols="1 768:2" x-gap="10" y-gap="10">
            <n-grid-item>
                <n-card title="账号权限">
                    <n-space justify="left">
                        <n-button @click="redirectToRoute('admin.group.list')">权限组列表</n-button>
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
