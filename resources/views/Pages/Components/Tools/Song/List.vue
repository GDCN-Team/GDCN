<template>
    <Modal :footer-hide="true" :mask-closable="false" v-model="show" title="歌曲列表">
        <Table :columns="columns" :data="songs.data">
            <!-- 歌曲ID -->
            <template slot-scope="{ row, index }" slot="song_id">
                <span v-if="editingIndex !== index">{{ row.song_id }}</span>
                <Input v-model="editing.song_id" v-else></Input>
            </template>

            <!-- 歌曲名 -->
            <template slot-scope="{ row, index }" slot="name">
                <Input v-model="editing.name" v-if="row.type !== 'netease' && editingIndex === index"></Input>
                <span v-else>{{ row.name }}</span>
            </template>

            <!-- 歌手名 -->
            <template slot-scope="{ row, index }" slot="author_name">
                <Input v-if="row.type !== 'netease' && editingIndex === index" v-model="editing.author_name"></Input>
                <span v-else>{{ row.author_name }}</span>
            </template>

            <template slot-scope="{ row }" slot="size">
                <span>{{ row.size }} MB</span>
            </template>

            <template slot-scope="{ row, index }" slot="action">
                <Button :to="row.download_url">试听</Button>
                <Button @click="edit(row, index)" v-if="editingIndex !== index">编辑</Button>
                <Button :loading="request2.loading" @click="saveEdit()" v-else>完成</Button>
                <Poptip
                    confirm
                    placement="left"
                    title="确认删除?"
                    @on-ok="deleteSong(row.id)">
                <Button :loading="request1.loading || false">删除</Button>
                </Poptip>
            </template>
        </Table>
        <Page :current="songs.current_page"
              @on-change="getSongs"
              class="mt-2"
              :total="songs.total"
              :page-size="songs.per_page"/>
    </Modal>
</template>

<script>
import {request} from "../../../../../js/helper";

export default {
    name: "List",
    mounted: function () {
        this.getSongs();
    },
    data: function () {
        return {
            show: true,
            songs: [],
            editingIndex: null,
            editing: {
                id: null,
                song_id: null,
                name: null,
                author_name: null
            },
            request1: {
                loading: false
            },
            request2: {
                loading: false
            },
            columns: [
                {
                    title: '歌曲ID',
                    slot: 'song_id'
                },
                {
                    title: '歌曲名',
                    slot: 'name'
                },
                {
                    title: '歌手名',
                    slot: 'author_name'
                },
                {
                    title: '大小',
                    slot: 'size'
                },
                {
                    title: '操作',
                    slot: 'action'
                }
            ]
        }
    },
    methods: {
        getSongs: function(page = 1) {
            const that = this;
            request('GET', '/api/tools/song/list', {
                data: {page},
                onSuccess: function(response) {
                    that.songs = response.data;
                }
            })
        },
        deleteSong: function(songID) {
            const that = this;
            request('DELETE', `/api/tools/song/delete/${songID}`, {
                request: that.request,
                onSuccess: function(response) {
                    that.getSongs();
                }
            })
        },
        edit: function(row, index) {
            this.editing.id = row.id;
            this.editing.song_id = row.song_id;
            this.editing.name = row.name;
            this.editing.author_name = row.author_name;
            this.editingIndex = index;
        },
        saveEdit: function() {
            const that = this;

            request('POST', '/api/tools/song/edit', {
                data: that.editing,
                onSuccess: function(response) {
                    that.editingIndex = null;
                    that.getSongs(that.songs.current_page);
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
