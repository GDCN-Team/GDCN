<template>
    <Layout class="layout">
        <Header>
            <Menu mode="horizontal" theme="dark" :active-name="currentPath">
                <menu-item to="/" name="home">
                    <Icon type="ios-home-outline"></Icon>主页
                </menu-item>
                <Submenu name="online">
                    <template slot="title">
                        <Icon type="md-reorder"></Icon>
                        在线工具
                    </template>
                    <menu-item to="/dashboard" name="dashboard">
                        <Icon type="ios-pie-outline"></Icon>Dashboard
                    </menu-item>
                    <menu-item to="/tools" name="tools">
                        <Icon type="ios-build-outline"></Icon>Tools
                    </menu-item>
                </Submenu>
            </Menu>
        </Header>
        <Content class="content">
            <div class="container">
                <slot></slot>
            </div>
        </Content>
        <Footer class="footer">
            <p class="text-sm">
                <a href="https://geometrydashchinese.com">几何冲刺玩家网</a>
                <a href="https://dl.geometrydashchinese.com">GDProxy</a>
                <a href="https://ng.geometrydashchinese.com">NGProxy</a>
            </p>
            <p class="text-xs">
                Copyright &copy; 2020 - @{{ new Date().getFullYear() }} <a href="https://gf.geometrydashchinese.com">GDCN</a>
                | <a
                href="https://beian.miit.gov.cn">吉ICP备18006293号</a>
            </p>
        </Footer>
    </Layout>
</template>

<script>
export default {
    name: "CommonLayout",
    mounted: function () {
        this.currentPath = location.pathname.split('/')[1] || 'home';
    },
    data: function () {
        return {
            currentPath: 'home'
        }
    }
}
</script>

<style scoped>
.layout {
    @apply dark:bg-gray-900 bg-gray-500 h-screen dark:text-white;
}

.content {
    @apply text-center overflow-auto;
}

.container {
    @apply lg:mt-10 mt-5 lg:w-3/5 px-2 mt-10 lg:mt-0 text-center mx-auto;
}

.footer {
    @apply dark:bg-gray-900 bg-gray-500 text-white text-center px-0;
}
</style>
