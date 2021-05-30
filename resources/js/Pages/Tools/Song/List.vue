<template>
    <layout>
        <a-card title="歌曲列表">
            <a-table :columns="columns" :data-source="songs" rowKey="id">
                <span slot="size" slot-scope="size">{{ size }} MB</span>
                <template slot="action" slot-scope="text, record">
                    <a-button :href="record.download_url">试听</a-button>
                    <a-button @click="editSong(record.id)">编辑</a-button>
                    <a-popconfirm cancel-text="我手滑了" ok-text="确定" title="确定删除?" @confirm="deleteSong(record.id)">
                        <a-button>删除</a-button>
                    </a-popconfirm>
                </template>
            </a-table>
        </a-card>

        <slot></slot>
    </layout>
</template>

<script>
import Layout from "../../Common/Layout/Web";

export default {
    name: "List",
    components: {
        Layout
    },
    props: {
        songs: Array
    },
    data() {
        return {
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
                    title: '上传者',
                    dataIndex: 'owner.name'
                },
                {
                    title: '操作',
                    dataIndex: 'action',
                    scopedSlots: {
                        customRender: 'action'
                    }
                }
            ]
        }
    },
    methods: {
        editSong: function (id) {
            this.$inertia.form({id}).post('/tools/song/edit');
        },
        deleteSong: function (id) {
            this.$inertia.form({id}).post('/tools/song/delete');
        }
    }
}
</script>
