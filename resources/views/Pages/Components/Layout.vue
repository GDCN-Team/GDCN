<template>
    <n-config-provider :theme="theme">
        <n-layout class="h-screen" position="absolute">
            <n-layout-header>
                <nav-bar @switch-theme="switchTheme"></nav-bar>
            </n-layout-header>
            <n-layout-content>
                <n-message-provider>
                    <message></message>
                </n-message-provider>

                <n-dialog-provider>
                    <Dialog></Dialog>
                </n-dialog-provider>

                <slot></slot>
            </n-layout-content>
            <n-layout-footer class="text-center w-full" position="absolute">
                <n-button text @click="redirectToRoute('home')">Geometry Dash Chinese</n-button> &copy; 2020 - {{
                    year
                }} |
                <n-button text @click="redirect('https://beian.miit.gov.cn')">吉ICP备18006293号</n-button>
            </n-layout-footer>
        </n-layout>
    </n-config-provider>
</template>

<script>
import NavBar from "./NavBar";
import {computed, ref} from "vue";
import {
    darkTheme,
    NButton,
    NConfigProvider,
    NDialogProvider,
    NLayout,
    NLayoutContent,
    NLayoutFooter,
    NLayoutHeader,
    NMessageProvider
} from 'naive-ui';
import {redirect, redirectToRoute} from "../../../js/helper";
import Message from "./Message";
import Dialog from "./Dialog";

export default {
    name: "Layout",
    computed: {
        year: function () {
            const date = new Date();
            return date.getFullYear();
        }
    },
    setup: function () {
        const isDarkTheme = computed(function () {
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        });

        const theme = ref(null);
        const switchTheme = function (dark = null) {
            theme.value = dark === true || theme.value === null ? darkTheme : null;
        }

        switchTheme(isDarkTheme);
        return {redirect, redirectToRoute, theme, switchTheme}
    },
    components: {
        Dialog,
        NavBar,
        NButton,
        NConfigProvider,
        NDialogProvider,
        NLayout,
        NLayoutContent,
        NLayoutFooter,
        NLayoutHeader,
        NMessageProvider,
        Message
    }
}
</script>
