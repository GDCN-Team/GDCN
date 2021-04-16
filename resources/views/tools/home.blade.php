<x-layout>
    <a-row :gutter="[10, 10]">
        <a-col :md="12" span="24">
            <a-card title="账号管理">
                <a-button href="{{ route('dashboard.account.setting') }}">修改用户名</a-button>
                <a-button href="{{ route('dashboard.account.update.password') }}">修改密码</a-button>
                <a-button href="{{ route('dashboard.account.setting') }}">修改邮箱</a-button>
                <a-button href="{{ route('tools.accounts.link') }}">账号链接</a-button>
            </a-card>
        </a-col>
        <a-col :md="12" span="24">
            <a-card title="关卡管理">
                <a-button href="{{ route('tools.levels.reupload') }}">关卡搬进</a-button>
                <a-button href="{{ route('tools.levels.level-to-gd') }}">关卡搬出</a-button>
            </a-card>
        </a-col>
        <a-col :md="12" span="24">
            <a-card title="歌曲管理">
                <a-button href="{{ route('tools.songs.upload.netease') }}">歌曲上传 - 网易专版</a-button>
                <a-button href="{{ route('tools.songs.list') }}">歌曲列表</a-button>
            </a-card>
        </a-col>
    </a-row>
</x-layout>
