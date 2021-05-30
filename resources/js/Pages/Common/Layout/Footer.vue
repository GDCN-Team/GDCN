<template>
    <a-layout-footer>
        GDCN | 2020 - {{ getCurrentYear() }} | <a href="//www.beian.miit.gov.cn">吉ICP备18006293号</a>
    </a-layout-footer>
</template>

<script>
export default {
    name: "Footer",
    methods: {
        getCurrentYear: function () {
            const date = new Date();
            return date.getFullYear();
        }
    }
}
</script>

<style scoped>

</style>
