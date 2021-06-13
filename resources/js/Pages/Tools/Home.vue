<template>
    <layout>
        <a-row :gutter="[10, 10]">
            <a-col :md="12" span="24">
                <a-card title="账号管理">
                    <inertia-link as="a-button" href="/tools/account/link">账号链接</inertia-link>
                </a-card>
            </a-col>
            <a-col :md="12" span="24">
                <a-card title="关卡管理">
                    <inertia-link as="a-button" href="/tools/level/trans:in">关卡搬进</inertia-link>
                    <inertia-link as="a-button" href="/tools/level/trans:out">关卡搬出</inertia-link>
                </a-card>
            </a-col>
            <a-col :md="12" span="24">
                <a-card title="歌曲管理">
                    <inertia-link as="a-button" href="/tools/song/upload:link">歌曲上传 - 外链版</inertia-link>
                    <inertia-link as="a-button" href="/tools/song/upload:netease">歌曲上传 - 网易专版</inertia-link>
                    <inertia-link as="a-button" href="/tools/song/list">歌曲列表</inertia-link>
                </a-card>
            </a-col>
        </a-row>

        <slot></slot>
    </layout>
</template>

<script>
import Layout from '../Common/Layout';

export default {
    name: "Home",
    components: {
        Layout
    }
}
</script>

<style scoped>

</style>
