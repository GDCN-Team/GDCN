<template>
    <layout>
        <img alt="Geometry Dash Chinese" class="w-full md:w-3/4 mx-auto" src="/storage/images/title.png">
        <br><br>
        <div class="w-full md:w-3/4 mx-auto">
            <a-alert banner type="info">
                <template v-slot:message>公告: GDCN公测! 如果您在游玩过程中遇到任何问题 请反馈给 <a
                    href="//wpa.qq.com/msgrd?uin=2331281251">渣渣120</a>
                </template>
            </a-alert>
        </div>
        <br>
        <a-card class="w-full md:w-3/4 mx-auto" title="下载">
            <a-button v-if="platform.match('Linux') !== null" href="//cdn.geometrydashchinese.com/download/GDCN.apk"
                      type="primary">
                <a-icon type="android"></a-icon>
                <span>Android</span>
            </a-button>
            <a-button-group v-else-if="platform.match('Win') !== null">
                <a-button href="//cdn.geometrydashchinese.com/download/GDCN.exe"
                          type="primary">
                    <a-icon type="windows"></a-icon>
                    <span>Windows(无资源包)</span>
                </a-button>
                <a-button href="//cdn.geometrydashchinese.com/download/GDCN.zip"
                          type="primary">
                    <a-icon type="windows"></a-icon>
                    <span>Windows(有资源包)</span>
                </a-button>
                <span class="pl-2">PS: 一般玩家请下载有资源包版本<br>无资源包版本需要手动导入资源包, 无法直接运行</span>
            </a-button-group>
            <h4 v-else>哦不, 我们无法判断您的设备, 请您在下方自行选择版本下载</h4>
            <br><br>
            <a-collapse>
                <a-collapse-panel header="选择其他版本">
                    <a-button-group class="text-center block">
                        <a-button href="//cdn.geometrydashchinese.com/download/GDCN.apk" type="primary">
                            <a-icon type="android"></a-icon>
                            <span>Android</span>
                        </a-button>
                        <a-dropdown>
                            <a-button type="primary">
                                <a-icon type="windows"></a-icon>
                                <span>Windows</span>
                            </a-button>

                            <a-menu slot="overlay">
                                <a-menu-item>
                                    <a href="//cdn.geometrydashchinese.com/download/GDCN.exe">无资源包</a>
                                </a-menu-item>
                                <a-menu-item>
                                    <a href="//cdn.geometrydashchinese.com/download/GDCN.zip">带资源包</a>
                                </a-menu-item>
                            </a-menu>
                        </a-dropdown>
                        <a-button disabled href="//cdn.geometrydashchinese.com/download/GDCN.ipa" type="primary">
                            <a-icon type="apple"></a-icon>
                            <span>IOS</span>
                        </a-button>
                    </a-button-group>
                </a-collapse-panel>
            </a-collapse>
        </a-card>
        <br>
        <div class="w-full md:w-3/4 mx-auto">
            <a-row :gutter="[10, 10]">
                <a-col :md="12" :span="24">
                    <a-card title="赞助 & 支持">
                        <a-button href="//afdian.net/@WOSHIZHAZHA120">爱发电</a-button>
                        <a-button href="//qm.qq.com/cgi-bin/qm/qr?k=ERVNMbKY3YBgTCngZ7YnhVWywlIvV8sq">加入GDCN - 讨论群
                        </a-button>
                    </a-card>
                </a-col>
                <a-col :md="12" :span="24">
                    <a-card title="在线工具">
                        <inertia-link as="a-button" href="/dashboard">Dashboard</inertia-link>
                        <inertia-link as="a-button" href="/tools">Tools</inertia-link>
                    </a-card>
                </a-col>
            </a-row>
            <br>
            <a-card class="text-left" title="GDCN团队">
                <a-descriptions title="渣渣120">
                    <a-descriptions-item label="QQ">
                        <a href="//wpa.qq.com/msgrd?uin=2331281251">2331281251</a>
                    </a-descriptions-item>
                    <a-descriptions-item label="哔哩哔哩">
                        <a href="//space.bilibili.com/24267334">渣渣120</a>
                    </a-descriptions-item>
                    <a-descriptions-item label="职责">
                        服主 / 前后端 / 运维
                    </a-descriptions-item>
                </a-descriptions>
                <a-descriptions title="xyzlol">
                    <a-descriptions-item label="QQ">
                        <a href="//wpa.qq.com/msgrd?uin=1292866784">1292866784</a>
                    </a-descriptions-item>
                    <a-descriptions-item label="哔哩哔哩">
                        <a href="//space.bilibili.com/93653653">xyz之谜</a>
                    </a-descriptions-item>
                    <a-descriptions-item label="职责">
                        副服主 / 规则制定 / 服务器管理
                    </a-descriptions-item>
                </a-descriptions>
            </a-card>
        </div>
    </layout>
</template>

<script>
import Layout from './Common/Layout';

export default {
    name: "Home",
    components: {
        Layout
    },
    data() {
        return {
            platform: navigator.platform
        }
    }
}
</script>

<style scoped>

</style>
