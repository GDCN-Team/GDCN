<template>
    <a-layout class="h-screen">
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
        <a-layout>
            <a-layout-content class="bg-gray-800 overflow-auto text-white p-3">
                <slot></slot>
            </a-layout-content>
            <a-layout-footer>
                GDCN | 2020 - {{ getCurrentYear() }} | <a href="https://beian.miit.gov.cn">吉ICP备18006293号</a>
            </a-layout-footer>
        </a-layout>
    </a-layout>
</template>

<script>
export default {
    watch: {
        '$page.props.notices': function () {
            this.loadNotices()
        }
    },
    mounted: function () {
        this.guessCurrentRoute();
        this.loadNotices();
    },
    methods: {
        guessCurrentRoute: function () {
            const array = window.location.pathname.split("/");
            return [array[1] || 'home'];
        },
        loadNotices: function () {
            const notices = this.$page.props.notices;
            for (let notice in notices) {
                if (!notices.hasOwnProperty(notice)) {
                    continue;
                }

                this.$notification[notices[notice].type]({
                    message: notices[notice].message,
                    description: notices[notice].description || ''
                });
            }
        },
        getCurrentYear: function () {
            const date = new Date();
            return date.getFullYear();
        }
    },
    name: "Layout"
}

</script>
