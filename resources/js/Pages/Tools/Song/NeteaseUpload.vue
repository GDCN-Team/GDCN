<template>
        <a-row :gutter="[10, 10]">
            <a-col :md="12" span="24">
                <a-card title="歌曲上传 - 网易专版">
                    <a-form-model :model="form"
                                  @submit="form.post('/tools/song/upload:netease');"
                                  @submit.native.prevent>

                        <!-- song ID -->
                        <a-form-model-item :help="errors.song_id"
                                           :validate-status="this.checkValidateStatus(errors.song_id, this.form)"
                                           has-feedback>
                            <a-input v-model="form.song_id" placeholder="歌曲ID" required type="number"></a-input>
                            <a-row :gutter="[10, 10]">
                                <a-col span="12">
                                    {{ result }}
                                </a-col>
                                <a-col class="text-right" span="12">
                                    <a-button type="link" @click="autoSongID()">自动选取歌曲ID</a-button>
                                </a-col>
                            </a-row>
                        </a-form-model-item>

                        <!-- custom Music ID -->
                        <a-row :gutter="[10, 10]">
                            <a-col span="12">
                                <a-form-model-item>
                                    <a-checkbox v-model="form.custom_music_id">
                                        使用其他音乐ID
                                    </a-checkbox>
                                </a-form-model-item>
                            </a-col>
                            <a-col span="12">
                                <!-- music ID -->
                                <a-form-model-item v-if="form.custom_music_id"
                                                   :help="errors.music_id"
                                                   :validate-status="this.checkValidateStatus(errors.music_id, this.form)"
                                                   has-feedback>
                                    <a-input v-model="form.music_id" placeholder="音乐ID" required
                                             type="number"></a-input>
                                </a-form-model-item>
                            </a-col>
                        </a-row>

                        <!-- submit -->
                        <a-form-model-item>
                            <a-button
                                :disabled="this.form.processing"
                                html-type="submit"
                                type="primary">
                                上传
                            </a-button>
                        </a-form-model-item>

                    </a-form-model>
                </a-card>
            </a-col>
            <a-col :md="12" span="24">
                <a-card title="歌曲搜索">
                    <a-input-search placeholder="搜索..." @search="searchMusic"></a-input-search>

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
</template>

<script>
import Layout from "../../Common/Layout";
import {checkValidateStatus} from "../../../Helpers";

export default {
    name: "NeteaseUpload",
    layout: Layout,
    props: {
        errors: Object,
        latestSongID: Number
    },
    data() {
        return {
            form: this.$inertia.form({
                song_id: null,
                music_id: null,
                custom_music_id: false
            }),
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
                result: {},
                pagination: {
                    current: 1,
                    total: 0
                }
            },
            result: ''
        }
    },
    methods: {
        checkValidateStatus,
        searchMusic: function (text) {
            const that = this;

            $.ajax({
                url: `https://s.music.163.com/search/get/?type=1&s=${text}&offset=${((this.search.pagination.current || 1) - 1) * 10}`,
                type: 'get',
                async: true,
                dataType: 'jsonp',
                success: function (data) {
                    that.search.result = data.result;
                    if (!data.result?.songs) {
                        that.search.pagination.current = 1;
                        that.searchMusic(text);
                    }


                    that.search.pagination.total = data.result?.songCount * 1;
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
        autoSongID: function () {
            window.Inertia.reload({
                only: ['latestSongID']
            });

            this.form.song_id = this.latestSongID;
        }
    }
}
</script>
