<template>
    <layout>
        <a-card title="歌曲列表">
            <a-table :columns="columns" :data-source="songs" rowKey="id">
                <template
                    v-for="col in ['song_id', 'name', 'author_name']"
                    :slot="col"
                    slot-scope="text, record">
                    <a-form-item
                        v-if="editingId === record.id"
                        :help="errors[col]"
                        :validate-status="checkValidateStatus(errors[col])">
                        <a-input
                            v-model="cache[col]"
                            style="margin: -5px 0">
                        </a-input>
                    </a-form-item>
                    <template v-else>
                        {{ text }}
                    </template>
                </template>
                <span slot="size" slot-scope="size">{{ size }} MB</span>
                <template slot="action" slot-scope="text, record">
                    <template v-if="editing && record.id === editingId">
                        <a-button-group>
                            <a-button @click="save">保存</a-button>
                            <a-button @click="cancel_edit">取消</a-button>
                        </a-button-group>
                    </template>
                    <template v-else>
                        <a-button-group>
                            <a-button :disabled="editing" :href="record.download_url" target="_blank">试听</a-button>
                            <template
                                v-if="editableTypes.indexOf(record.type) > -1 && record.owner.id === $page.props.auth.user.id">
                                <a-button :disabled="editing" @click="edit(record.id)">编辑</a-button>
                                <a-popconfirm
                                    cancel-text="我手滑了"
                                    ok-text="确定"
                                    title="确定删除?"
                                    @confirm="this.$inertia.delete(`/tools/song/${record.id}`, {only: ['songs']})">
                                    <a-button :disabled="editing">删除</a-button>
                                </a-popconfirm>
                            </template>
                        </a-button-group>
                    </template>
                </template>
            </a-table>
        </a-card>

        <slot></slot>
    </layout>
</template>

<script>
import Layout from "../../Common/Layout";
import {checkValidateStatus} from '../../../Helpers';

export default {
    name: "List",
    components: {
        Layout
    },
    props: {
        songs: Array,
        editableTypes: Array,
        errors: Object
    },
    data() {
        return {
            columns: [
                {
                    title: '歌曲ID',
                    dataIndex: 'song_id',
                    scopedSlots: {
                        customRender: 'song_id'
                    }
                },
                {
                    title: '歌曲名',
                    dataIndex: 'name',
                    scopedSlots: {
                        customRender: 'name'
                    }
                },
                {
                    title: '歌手名',
                    dataIndex: 'author_name',
                    scopedSlots: {
                        customRender: 'author_name'
                    }
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
            ],
            editingId: 0,
            editing: false,
            cache: {}
        }
    },
    methods: {
        checkValidateStatus,
        edit: function (id) {
            this.cache = {...this.songs.find(s => id === s.id)};
            this.editingId = id;
            this.editing = true;
        },
        save: function () {
            this.$inertia.form({
                song_id: this.cache.song_id,
                name: this.cache.name,
                author_name: this.cache.author_name
            }).post(`/tools/song/edit/${this.cache.id}`);
        },
        cancel_edit: function () {
            this.editing = false;
            this.editingId = 0;
            this.cache = {};
            this.$forceUpdate();
        }
    }
}
</script>
