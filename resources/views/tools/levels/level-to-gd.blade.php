<x-layout>
    <page></page>
</x-layout>

<script id="page" type="text/x-template">
    <div>
        <a-card title="关卡搬出">
            <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                <a-form-model-item label="服务器">
                    <a-select v-model="form.server">
                        <a-select-option value="official">
                            官服
                        </a-select-option>
                        <a-select-option value="custom">
                            自定义
                        </a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item v-if="form.server === 'custom'">
                    <a-input v-model="form.custom_server_url" type="url" placeholder="自定义服务器地址"></a-input>
                    <span>示例地址（官服）: http://www.boomlings.com/database/uploadGJLevel21.php</span>
                </a-form-model-item>
                <a-form-model-item label="上传到">
                    <a-select v-model="form.linkID">
                        <a-select-option v-for="link in linkedAccounts" :value="link.id">
                            @{{ link.target_name }}
                        </a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item>
                    <a-input v-model="form.password" type="password" placeholder="密码"></a-input>
                </a-form-model-item>
                <a-form-model-item>
                    <a-input v-model="form.levelID" type="text" placeholder="关卡ID"></a-input>

                    <a href="javascript:" @click="showChangeSongDrawer = !showChangeSongDrawer">换歌</a>
                </a-form-model-item>
                <a-form-model-item>
                    <a-button type="primary" html-type="submit" :loading="request.loading" :disabled="
                !form.server || !form.levelID ||
                (form.server === 'custom' && !form.custom_server_url)">
                        搬运
                    </a-button>
                </a-form-model-item>
            </a-form-model>
        </a-card>

        <a-drawer title="换歌" :visible="showChangeSongDrawer">
            <a-form-model :model="form">
                <a-form-model-item label="类型">
                    <a-select v-model="form.songType">
                        <a-select-option value="original">
                            不变
                        </a-select-option>
                        <a-select-option value="official">
                            官方歌曲
                        </a-select-option>
                        <a-select-option value="newgrounds">
                            NG歌曲
                        </a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item v-if="form.songType === 'official'" label="歌曲">
                    <a-select v-model="form.songID">
                        <a-select-option value="-1">Practice: Stay Inside Me - OcularNebula</a-select-option>
                        <a-select-option value="0">Stereo Madness - Foreverbound</a-select-option>
                        <a-select-option value="1">Back on Track - DJVI</a-select-option>
                        <a-select-option value="2">Polargeist - Step</a-select-option>
                        <a-select-option value="3">Dry Out - DJVI</a-select-option>
                        <a-select-option value="4">Base after Base - DJVI</a-select-option>
                        <a-select-option value="5">Cant Let Go - DJVI</a-select-option>
                        <a-select-option value="6">Jumper - Waterflame</a-select-option>
                        <a-select-option value="7">Time Machine - Waterflame</a-select-option>
                        <a-select-option value="8">Cycles - DJVI</a-select-option>
                        <a-select-option value="9">xStep - DJVI</a-select-option>
                        <a-select-option value="10">Clutterfunk - Waterflame</a-select-option>
                        <a-select-option value="11">Theory of Everything - DJ-Nate</a-select-option>
                        <a-select-option value="12">Electroman Adventures - Waterflame</a-select-option>
                        <a-select-option value="13">Clubstep - DJ-Nate</a-select-option>
                        <a-select-option value="14">Electrodynamix - DJ-Nate</a-select-option>
                        <a-select-option value="15">Hexagon Force - Waterflame</a-select-option>
                        <a-select-option value="16">Blast Processing - Waterflame</a-select-option>
                        <a-select-option value="17">Theory of Everything 2 - DJ-Nate</a-select-option>
                        <a-select-option value="18">Geometrical Dominator - Waterflame</a-select-option>
                        <a-select-option value="19">Deadlocked - F-777</a-select-option>
                        <a-select-option value="20">Fingerdash - MDK</a-select-option>
                        <a-select-option value="21">The Seven Seas - F-777</a-select-option>
                        <a-select-option value="22">Viking Arena - F-777</a-select-option>
                        <a-select-option value="23">Airborne Robots - F-777</a-select-option>
                        <a-select-option value="24">The Challenge - RobTop</a-select-option>
                        <a-select-option value="25">Payload	Dex - Arson</a-select-option>
                        <a-select-option value="26">Beast Mode - Dex Arson</a-select-option>
                        <a-select-option value="27">Machina - Dex Arson</a-select-option>
                        <a-select-option value="28">Years - Dex Arson</a-select-option>
                        <a-select-option value="29">Frontlines - Dex Arson</a-select-option>
                        <a-select-option value="30">Space Pirates - Waterflame</a-select-option>
                        <a-select-option value="31">Striker - Waterflame</a-select-option>
                        <a-select-option value="32">Embers - Dex Arson</a-select-option>
                        <a-select-option value="33">Round 1 - Dex Arson</a-select-option>
                        <a-select-option value="34">Monster Dance Off - F-777</a-select-option>
                        <a-select-option value="35">Press Start - MDK</a-select-option>
                        <a-select-option value="36">Nock Em - Bossfight</a-select-option>
                        <a-select-option value="37">Power Trip - Boom Kitty</a-select-option>
                    </a-select>
                </a-form-model-item>
                <a-form-model-item label="歌曲ID" v-if="form.songType === 'newgrounds'">
                    <a-input v-model="form.songID" type="number" placeholder="歌曲ID"></a-input>
                </a-form-model-item>
            </a-form-model>
        </a-drawer>
    </div>
</script>

<script>
    window.Vue.component('page', {
        template: `#page`,
        mounted: function () {
            this.getLinkedAccounts();
        },
        data: function () {
            return {
                form: {
                    server: 'official',
                    custom_server_url: null,

                    linkID: '',
                    password: '',

                    songType: 'original',
                    songID: '0',

                    levelID: null
                },

                linkedAccounts: [],
                showChangeSongDrawer: false,

                request: {
                    url: '{{ route('web.api.v1.tools.levels.level-to-gd') }}',
                    data: this.form,
                    default_success_text: '上传成功!',
                    loading: false,
                }
            }
        },
        methods: {
            getLinkedAccounts: function () {
                const that = this;
                window.$request({
                    url: '{{ route('web.api.v1.tools.accounts.link.list') }}',
                    default_success_text: '链接账号列表获取成功!'
                }, function (response) {
                    that.linkedAccounts = response.data;
                    that.form.linkID = response.data[0]?.id
                });
            },
            submit: function () {
                this.request.data = this.form;
                window.$request(this.request, function (response) {
                    app.$message.info('关卡ID: ' + response.data.id);
                });
            }
        }
    });
</script>
