<x-layout class="text-center">
    <img class="w-full md:w-3/4" src="{{ asset('images/title.png') }}" alt="Geometry Dash Chinese">
    <br><br>
    <div class="w-full md:w-3/4 mx-auto">
        <a-alert banner type="info">
            <template v-slot:message>公告: GDCN公测! 如果您在游玩过程中遇到任何问题 请反馈给 <a href="//wpa.qq.com/msgrd?uin=2331281251">渣渣120</a></template>
        </a-alert>
    </div>
    <br>
    <a-button-group>
        <a-button type="primary" href="//cdn.geometrydashchinese.com/download/GDCN.apk">
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
        <a-button disabled type="primary" href="//cdn.geometrydashchinese.com/download/GDCN.ipa">
            <a-icon type="apple"></a-icon>
            <span>IOS</span>
        </a-button>
    </a-button-group>
    <br><br>
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
                    <a-button href="{{ route('dashboard.home') }}">Dashboard</a-button>
                    <a-button href="{{ route('tools.home') }}">Tools</a-button>
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
</x-layout>
