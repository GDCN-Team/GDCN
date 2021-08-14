<template>
    <layout>
        <div class="container p-2 lg:mx-auto">
            <img :src="server.cdn_url + '/images/title.png'" :alt="server.app_name">

            <n-grid x-gap="10" y-gap="10" cols="1 768:2 1024:4" class="mt-5">
                <n-grid-item :span="2">
                    <n-card title="GDCN 介绍">
                        <p class="font-bold">
                            GDCN 是由渣渣120制作的开源
                            <n-button text @click="redirect('https://store.steampowered.com/app/322170')">
                                Geometry Dash(几何冲刺)
                            </n-button>
                            私服
                            <br>
                            开源地址:
                            <n-button text @click="redirect('https://github.com/GDCN-Team/GDCN')">
                                https://github.com/GDCN-Team/GDCN
                            </n-button>
                            <br><br>
                            <span class="text-red-700">重要: 私服仅供娱乐, 不能代替官服, 有能力的可以自行购买正版游玩</span>
                        </p>
                    </n-card>
                </n-grid-item>
                <n-grid-item :span="2">
                    <n-card title="客户端下载">
                        <span class="font-bold">Tips: Windows的无资源包版本藏到右键里了哦</span>
                        <br>
                        <n-button-group class="mt-8">
                            <n-button @click="redirect(server.cdn_url + '/../download/GDCN.apk')">
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
                                <n-button @click="redirect(server.cdn_url + '/../download/GDCN.zip')"
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
                                <template @click="redirect(server.cdn_url + '/../download/GDCN.ipa')" #icon>
                                    <n-icon>
                                        <apple-outlined></apple-outlined>
                                    </n-icon>
                                </template>

                                IOS
                            </n-button>
                        </n-button-group>
                    </n-card>
                </n-grid-item>
                <n-grid-item :span="2">
                    <n-card title="赞助 & 支持">
                        <n-space justify="left">
                            <n-button @click="redirect('https://afdian.net/@WOSHIZHAZHA120')">
                                <template #icon>
                                    <n-icon>
                                        <heart-outlined></heart-outlined>
                                    </n-icon>
                                </template>

                                爱发电
                            </n-button>
                            <n-button
                                @click="redirect('https://qm.qq.com/cgi-bin/qm/qr?k=Bzo6uH4ExL3xnvK5tEm0mda5HpyA7kuK')">
                                <template #icon>
                                    <n-icon>
                                        <usergroup-add-outlined></usergroup-add-outlined>
                                    </n-icon>
                                </template>

                                加入讨论群
                            </n-button>
                        </n-space>
                    </n-card>
                </n-grid-item>
                <n-grid-item :span="2">
                    <n-card title="在线工具">
                        <n-space justify="left">
                            <n-button @click="redirectToRoute('dashboard.home')">
                                <template #icon>
                                    <n-icon>
                                        <dashboard></dashboard>
                                    </n-icon>
                                </template>

                                Dashboard
                            </n-button>
                            <n-button @click="redirectToRoute('tools.home')">
                                <template #icon>
                                    <n-icon>
                                        <tools></tools>
                                    </n-icon>
                                </template>

                                Tools
                            </n-button>
                        </n-space>
                    </n-card>
                </n-grid-item>
            </n-grid>
            <n-card class="mt-2.5" title="GDCN 团队">
                <n-grid x-gap="10" y-gap="10" cols="1 768:2">
                    <n-grid-item>
                        <n-descriptions :column="1" title="xyzlol">
                            <n-descriptions-item label="职位">
                                副服主 | 规则制定 | 运维
                            </n-descriptions-item>
                            <n-descriptions-item label="QQ">
                                <n-button text @click="redirect('https://wpa.qq.com/msgrd?uin=1292866784')">
                                    1292866784
                                </n-button>
                            </n-descriptions-item>
                            <n-descriptions-item label="哔哩哔哩">
                                <n-button text @click="redirect('https://space.bilibili.com/93653653')">
                                    https://space.bilibili.com/93653653
                                </n-button>
                            </n-descriptions-item>
                        </n-descriptions>
                    </n-grid-item>
                    <n-grid-item>
                        <n-descriptions :column="1" title="渣渣120">
                            <n-descriptions-item label="职位">
                                服主 | 前后端开发 | 运维
                            </n-descriptions-item>
                            <n-descriptions-item label="QQ">
                                <n-button text @click="redirect('https://wpa.qq.com/msgrd?uin=2331281251')">
                                    2331281251
                                </n-button>
                            </n-descriptions-item>
                            <n-descriptions-item label="哔哩哔哩">
                                <n-button text @click="redirect('https://space.bilibili.com/24267334')">
                                    https://space.bilibili.com/24267334
                                </n-button>
                            </n-descriptions-item>
                        </n-descriptions>
                    </n-grid-item>
                </n-grid>
            </n-card>
        </div>
    </layout>
</template>

<script>
import Layout from "./Components/Layout";
import {
    NButton,
    NButtonGroup,
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NDropdown,
    NGrid,
    NGridItem,
    NIcon,
    NSpace
} from "naive-ui";
import {AndroidOutlined, AppleOutlined, HeartOutlined, UsergroupAddOutlined, WindowsOutlined} from "@vicons/antd";
import {h, reactive} from "vue";
import {Dashboard, Tools} from "@vicons/carbon";
import {invertValue, redirect, redirectToRoute} from "../../js/helper";

export default {
    name: "Home",
    props: {
        server: {
            app_name: String,
            cdn_url: String
        }
    },
    setup: function (props) {
        const windows_downloads = reactive({
            show: false,
            options: [
                {
                    label: () => h('a', {
                        onClick: () => this.redirect(props.server.cdn_url + '/../download/GDCN.exe')
                    }, '下载无资源包版本'),
                    key: 'no-resource-version'
                }
            ]
        })

        return {
            windows_downloads,
            redirect,
            redirectToRoute,
            invertValue
        }
    },
    components: {
        Layout,
        NCard,
        NGrid,
        NGridItem,
        NButtonGroup,
        NButton,
        NIcon,
        AndroidOutlined,
        WindowsOutlined,
        AppleOutlined,
        NDropdown,
        NSpace,
        HeartOutlined,
        UsergroupAddOutlined,
        Dashboard,
        Tools,
        NDescriptions,
        NDescriptionsItem
    }
}
</script>
