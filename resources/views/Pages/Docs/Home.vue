<template>
    <page-layout title="文档站">
        <n-card>
            <n-button @click="redirectToRoute('docs.commands')">命令</n-button>
        </n-card>
    </page-layout>
</template>

<script>
import {redirectToRoute} from '../../../js/helper';
import PageLayout from "../Components/PageLayout";
import {NButton, NCard} from "naive-ui";

export default {
    name: "Home",
    setup: function () {
        return {redirectToRoute};
    },
    components: {PageLayout, NCard, NButton}
}
</script>

<style scoped>

</style>
