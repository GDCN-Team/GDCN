<template>
    <page-layout class="w-1/3" title="Geometry Dash Proxy (GDProxy)">
        <n-card>
            <p>Geometry Dash Proxy (NGProxy) 是 Geometry Dash Chinese (GDCN) 附属的一项服务,
                可以为您提供Geometry Dash加速链接的服务</p>
            <br>
            <h2 class="text-lg font-bold inline-block pr-2.5">客户端下载: </h2>
            <n-button-group>
                <n-button @click="redirect(server.cdn_url + '/../download/GDProxy.apk')">
                    <template #icon>
                        <n-icon>
                            <android-outlined></android-outlined>
                        </n-icon>
                    </template>

                    Android
                </n-button>

                <n-dropdown :on-clickoutside="() => {windows_downloads.show = false}"
                            :options="windows_downloads.options"
                            :show="windows_downloads.show"
                            trigger="manual">
                    <n-button @click="redirect(server.cdn_url + '/../download/GDProxy.zip')"
                              @contextmenu.prevent="invertValue(windows_downloads, 'show')">
                        <template #icon>
                            <n-icon>
                                <windows-outlined></windows-outlined>
                            </n-icon>
                        </template>

                        Windows
                    </n-button>
                </n-dropdown>

                <n-button disabled>
                    <template @click="redirect(server.cdn_url + '/../download/GDProxy.ipa')" #icon>
                        <n-icon>
                            <apple-outlined></apple-outlined>
                        </n-icon>
                    </template>

                    IOS
                </n-button>
            </n-button-group>
        </n-card>
    </page-layout>
</template>

<script>

import PageLayout from "../Components/PageLayout";
import {NButton, NButtonGroup, NCard, NDropdown, NIcon} from "naive-ui";
import {AndroidOutlined, AppleOutlined, WindowsOutlined} from "@vicons/antd";
import {h, reactive} from "vue";
import {invertValue, redirect} from "../../../js/helper";

export default {
    name: "Home",
    props: {
        server: Object
    },
    setup: function (props) {
        const windows_downloads = reactive({
            show: false,
            options: [
                {
                    label: () => h('a', {
                        onClick: () => this.redirect(props.server.cdn_url + '/../download/GDProxy.exe')
                    }, '下载无资源包版本'),
                    key: 'no-resource-version'
                }
            ]
        });

        return {windows_downloads, invertValue, redirect}
    },
    components: {
        PageLayout,
        NCard,
        NButtonGroup,
        NButton,
        NDropdown,
        NIcon,
        AndroidOutlined,
        WindowsOutlined,
        AppleOutlined
    }
}
</script>
