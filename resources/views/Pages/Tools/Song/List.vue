<template>
    <page-layout title="歌曲列表">
        <n-card>
            <n-space justify="space-between">
                <n-button href="?me=1" tag="a">我上传的</n-button>
                <n-space>
                    <n-input v-model:value="searchSongForm.search" placeholder="搜索..."></n-input>
                    <n-button
                        @click="searchSongForm.get(route('tools.song.list'), { only: ['songs'], preserveState: true})">
                        搜索
                    </n-button>
                </n-space>
            </n-space>
            <br>
            <n-data-table
                :columns="columns"
                :data="songs.data"
                :loading="updatePageForm.processing"
                :pagination="pagination"
                remote
                @update:page="updatePage"
            ></n-data-table>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {NButton, NButtonGroup, NCard, NDataTable, NInput, NSpace, NText} from "naive-ui";
import {computed, h} from "vue";
import {formatTime, redirect, redirectToRoute} from "../../../../js/helper";
import {useForm, usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "List",
    props: {
        songs: Object,
        accountID: Number
    },
    setup: function (props) {
        const deleteForm = useForm({});

        const columns = [
            {
                title: '歌曲ID',
                key: 'song_id'
            },
            {
                title: '歌曲名',
                key: 'name'
            },
            {
                title: '歌手名',
                key: 'author_name'
            },
            {
                title: '上传者',
                key: 'uploader.name',
                render: function (row) {
                    return h(NText, null, {
                        default: () => row.uploader.name
                    });
                }
            },
            {
                title: '大小',
                key: 'size',
                render: function (row) {
                    return h(NText, null, {
                        default: () => row.size + ' MB'
                    });
                }
            },
            {
                title: '上传时间',
                key: 'created_at',
                render: function (row) {
                    return h(NText, null, {
                        default: () => formatTime(row.created_at, '未知')
                    })
                }
            },
            {
                title: '最后编辑时间',
                key: 'updated_at',
                render: function (row) {
                    return h(NText, null, {
                        default: () => formatTime(row.updated_at, '无')
                    })
                }
            },
            {
                title: '操作',
                key: 'action',
                render: function (row) {
                    return h(NButtonGroup, null, function () {
                        return [
                            h(NButton, {
                                onClick: () => redirect(row.download_url)
                            }, {
                                default: () => '试听'
                            }),
                            function () {
                                if (row.uploader.id === props.accountID) {
                                    return h(NButton, {
                                        onClick: () => redirectToRoute('tools.song.edit', row.id)
                                    }, {
                                        default: () => '编辑'
                                    });
                                }
                            }(),
                            function () {
                                if (row.uploader.id === props.accountID) {
                                    return h(NButton, {
                                        type: 'error',
                                        loading: deleteForm.processing,
                                        onClick: () => deleteForm.delete($route('tools.song.delete.api', row.id))
                                    }, {
                                        default: () => '删除'
                                    });
                                }
                            }()
                        ];
                    })
                }
            }
        ];

        const updatePageForm = useForm({});
        const updatePage = function (page) {
            updatePageForm.get($route('tools.song.list', {_query: {page}}));
        }

        const pagination = computed(function () {
            const $page = usePage();

            return {
                page: $page.props.value.songs.current_page,
                pageSize: $page.props.value.songs.per_page,
                itemCount: $page.props.value.songs.total
            }
        });

        const searchSongForm = useForm({
            search: null
        });

        return {columns, pagination, updatePage, updatePageForm, searchSongForm}
    },
    components: {
        PageLayout,
        NCard,
        NButton,
        NDataTable,
        NSpace,
        NInput
    }
}
</script>
