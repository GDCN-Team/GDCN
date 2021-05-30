<template>
    <a-layout-sider breakpoint="md" collapsed-width="0">
        <a-menu :default-selected-keys="guessCurrentRoute()" mode="vertical" theme="dark">

            <a-menu-item key="home">
                <inertia-link href="/">
                    <a-icon type="home"></a-icon>
                    <span>主页</span>
                </inertia-link>
            </a-menu-item>

            <a-menu-item key="dashboard">
                <inertia-link href="/dashboard">
                    <a-icon type="dashboard"></a-icon>
                    <span>Dashboard</span>
                </inertia-link>
            </a-menu-item>

            <a-menu-item key="tools">
                <inertia-link href="/tools">
                    <a-icon type="tool"></a-icon>
                    <span>Tools</span>
                </inertia-link>
            </a-menu-item>
        </a-menu>
    </a-layout-sider>
</template>

<script>
export default {
    name: "Sider",
    methods: {
        guessCurrentRoute: function () {
            const array = window.location.pathname.split("/");
            return [array[1] || 'home'];
        }
    }
}
</script>

<style scoped>

</style>
