<template>
    <page-layout class="lg:w-2/3" title="Tools">
        <n-grid x-gap="10" y-gap="10" cols="1 768:2">
            <n-grid-item>
                <n-card title="账号">
                    <n-space justify="left">
                        <n-button @click="redirectToRoute('tools.account.link')">账号链接</n-button>
                        <n-button @click="redirectToRoute('tools.account.link.list')">查看已链接账号</n-button>
                    </n-space>
                </n-card>
            </n-grid-item>

            <n-grid-item>
                <n-card title="关卡">
                    <n-space justify="left">
                        <n-button @click="redirectToRoute('tools.level.trans.in')">关卡搬进</n-button>
                        <n-button @click="redirectToRoute('tools.level.trans.out')">关卡搬出</n-button>
                    </n-space>
                </n-card>
            </n-grid-item>

            <n-grid-item>
                <n-card title="歌曲">
                    <n-space justify="left">
                        <n-button @click="redirectToRoute('tools.song.upload.link')">歌曲上传(外链版)</n-button>
                        <n-button @click="redirectToRoute('tools.song.upload.netease')">歌曲上传(网易云专版)</n-button>
                        <n-button @click="redirectToRoute('tools.song.list')">歌曲列表</n-button>
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
        NSpace,
        NButton,
        NGrid,
        NGridItem
    }
}
</script>
