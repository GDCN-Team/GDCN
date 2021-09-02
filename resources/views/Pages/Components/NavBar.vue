<template>
    <n-space :justify="justify">
        <n-menu
            v-model:value="menu.left.active"
            :options="menu.left.options"
            mode="horizontal"/>

        <n-menu
            v-model:value="menu.right.active"
            :options="menu.right.options"
            mode="horizontal"/>
    </n-space>
</template>

<script>
import {h} from "vue";
import {ColorSwitch, Dashboard, Home, Tools, User} from "@vicons/carbon";
import {NIcon, NMenu, NSpace} from "naive-ui";
import {isMobile} from "../../../js/helper";

export default {
    name: "NavBar",
    computed: {
        justify: function () {
            return isMobile() ? 'space-between' : 'center';
        }
    },
    setup: function (props, context) {
        const renderMenuIcon = function (icon) {
            return function () {
                return h(NIcon, null, {
                    default: () => h(icon)
                });
            };
        }

        const menu = {
            left: {
                active: $route().current(),
                options: [
                    { // 主页
                        label: () => h('a', {
                            href: $route('home')
                        }, '主页'),
                        key: 'home',
                        icon: renderMenuIcon(Home)
                    },
                    { // Dashboard
                        label: () => h('a', {
                            href: $route('dashboard.home')
                        }, 'Dashboard'),
                        key: 'dashboard.home',
                        icon: renderMenuIcon(Dashboard)
                    },
                    { // Tools
                        label: () => h('a', {
                            href: $route('tools.home')
                        }, 'Tools'),
                        key: 'tools.home',
                        icon: renderMenuIcon(Tools)
                    }
                ]
            },
            right: {
                active: $route().current(),
                options: [
                    { // 换个主题
                        label: () => h('a', {
                            href: 'javascript:',
                            onClick: () => context.emit('switchTheme')
                        }, '换个主题'),
                        key: 'color.switch',
                        icon: renderMenuIcon(ColorSwitch)
                    },
                    { // 个人中心
                        label: () => h('a', {
                            href: $route('dashboard.profile')
                        }, '个人中心'),
                        key: 'dashboard.profile',
                        icon: renderMenuIcon(User)
                    }
                ]
            }
        }

        return {menu}
    },
    components: {
        NSpace,
        NMenu,
        Home,
        Dashboard,
        Tools
    }
}
</script>
