<x-layout>
    <page></page>
</x-layout>

<script id="page" type="text/x-template">
    <a-card title="歌曲列表">
        <a-table row-key="song_id" :columns="columns" :data-source="data">
            <template slot="size" slot-scope="size">@{{ size }} MB</template>
            <template slot="action" slot-scope="song">
                <a :href="song.download_url">预览</a>
                <template v-if="song.uploader === accountID">
                    <a-popconfirm
                        title="确定删除吗"
                        ok-text="确定"
                        cancel-text="取消"
                        @confirm="deleteSong(song.song_id)">
                        <a href="javascript:">删除</a>
                    </a-popconfirm>
                </template>
            </template>
        </a-table>
    </a-card>
</script>

<script>
    window.Vue.component('page', {
        template: `#page`,
        data: function () {
            return {
                accountID: {{ Auth::id() }},
                columns: [
                    {
                        title: '歌曲ID',
                        dataIndex: 'song_id'
                    },
                    {
                        title: '歌曲名',
                        dataIndex: 'name'
                    },
                    {
                        title: '歌手名',
                        dataIndex: 'author_name'
                    },
                    {
                        title: '大小',
                        dataIndex: 'size',
                        scopedSlots: {
                            customRender: 'size'
                        }
                    },
                    {
                        title: '操作',
                        scopedSlots: {
                            customRender: 'action'
                        }
                    }
                ],
                data: []
            }
        },
        mounted: function () {
            this.getSongList()
        },
        methods: {
            getSongList: function() {
                const that = this;
                window.$request({
                    url: '{{ route('web.api.v1.tools.songs.list') }}',
                    default_success_text: '歌曲列表获取成功!'
                }, function(response) {
                    that.data = response.data;
                });
            },
            deleteSong: function (songID) {
                const that = this;

                window.$request({
                    url: '{{ route('web.api.v1.tools.songs.delete') }}',
                    data: {songID},
                    default_success_text: '删除成功!',
                }, function () {
                    that.getSongList();
                });
            }
        }
    })
</script>
