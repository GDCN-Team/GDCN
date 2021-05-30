<template>
    <layout>
        <a-row :gutter="[10, 10]">
            <a-col :md="12" span="24">
                <a-card title="歌曲上传 - 网易专版">
                    <a-form-model :model="form" @submit="submit" @submit.native.prevent>
                        <Input :errors="errors" :form="form" name="song_id" placeholder="自定义歌曲ID"
                               type="number">
                            <a-row slot="extra" :gutter="[10, 10]">
                                <a-col span="12">
                                    {{ result }}
                                </a-col>
                                <a-col class="text-right" span="12">
                                    <a-button type="link" @click="autoSongID()">自动选取歌曲ID</a-button>
                                </a-col>
                            </a-row>
                        </Input>
                        <submit-bottom :check="check" text="上传"></submit-bottom>
                    </a-form-model>
                </a-card>
            </a-col>
            <a-col :md="12" span="24">
                <a-card title="歌曲搜索">
                    <a-input v-model="search.text" placeholder="搜索..." type="text"></a-input>

                    <template v-if="search.result">
                        <a-table :columns="columns" :data-source="search.result.songs" :pagination="search.pagination"
                                 row-key="id"
                                 @change="searchMusic">
                            <template slot="artist" slot-scope="text, record">
                                {{ mergeArtistNames(record) }}
                            </template>
                            <template slot="action" slot-scope="text, record">
                                <a-space>
                                    <a-button :href="record.page">详情</a-button>
                                    <a-button @click="selectMusic(record)">选择</a-button>
                                </a-space>
                            </template>
                        </a-table>
                    </template>
                </a-card>
            </a-col>
        </a-row>
    </layout>
</template>

<script>
import Layout from "../../Common/Layout/Web";
import Input from "../../Common/Form/Input";
import SubmitBottom from "../../Common/Form/SubmitBottom";

export default {
    name: "NeteaseUpload",
    components: {
        Layout,
        Input,
        SubmitBottom
    },
    watch: {
        'search.text': function () {
            if (this.timer) {
                clearTimeout(this.timer)
            }

            this.timer = setTimeout(() => this.searchMusic(), 500)
        }
    },
    props: {
        errors: Object,
        latestSongID: Number
    },
    data() {
        return {
            form: {
                song_id: '',
                music_id: ''
            },
            columns: [
                {
                    title: '歌曲ID',
                    dataIndex: 'id'
                },
                {
                    title: '歌曲名',
                    dataIndex: 'name'
                },
                {
                    title: '歌手名',
                    dataIndex: 'artists',
                    scopedSlots: {
                        customRender: 'artist'
                    }
                },
                {
                    title: '专辑名',
                    dataIndex: 'album.name'
                },
                {
                    title: '操作',
                    dataIndex: 'action',
                    scopedSlots: {
                        customRender: 'action'
                    }
                }
            ],
            search: {
                text: '',
                result: null,
                pagination: {
                    current: 1,
                    total: 10
                }
            },
            result: '',
            timer: null
        }
    },
    methods: {
        check: function () {
            return this.form.song_id === '' || this.form.music_id === '';
        },
        searchMusic: function (pagination = null) {
            const that = this;

            if (pagination) {
                this.search.pagination = pagination;
            }

            $.ajax({
                url: 'http://s.music.163.com/search/get/?type=1&s=' + this.search.text + '&offset=' + (this.search.pagination.current - 1) * 10,
                type: 'get',
                async: true,
                dataType: 'jsonp',
                success: function (data) {
                    that.search.result = data.result;
                    if (!data.result?.songs) {
                        that.search.pagination.current = 1;
                        that.searchMusic();
                    }

                    that.search.pagination.total = data.result?.songCount;
                }
            })
        },
        selectMusic(song) {
            this.form.music_id = song.id;
            this.result = '已选择: ' + song.name + ' - ' + this.mergeArtistNames(song);
        },
        mergeArtistNames: function (song) {
            let name = [];
            for (let i = 0; i < song.artists.length; i++) {
                name.push(song.artists[i].name);
            }

            return name.join(' / ');
        },
        submit: function () {
            this.$inertia.form(this.form).post('/tools/song/upload:netease');
        },
        autoSongID: function () {
            window.Inertia.reload({
                only: ['latestSongID']
            });

            this.form.song_id = this.latestSongID;
        }
    }
}
</script>
