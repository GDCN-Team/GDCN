<template>
    <page-layout class="lg:w-2/3" title="歌曲列表">
        <n-card>
            <n-space justify="space-between">
                <n-button tag="a" href="?me=1">我上传的</n-button>
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
                remote
                :columns="columns"
                :data="songs.data"
                :pagination="pagination"
                :loading="updatePageForm.processing"
                @update:page="updatePage"
            ></n-data-table>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {NButton, NButtonGroup, NCard, NDataTable, NInput, NSpace, NText} from "naive-ui";
import {h} from "vue";
import {redirect, redirectToRoute} from "../../../../js/helper";
import {useForm} from "@inertiajs/inertia-vue3";

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

        const pagination = {
            page: props.songs.current_page,
            pageSize: props.songs.per_page,
            itemCount: props.songs.total
        }

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
