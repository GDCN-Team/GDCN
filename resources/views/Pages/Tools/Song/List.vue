<template>
    <page-layout class="lg:w-2/3" title="链接列表">
        <n-card>
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
import {NButton, NButtonGroup, NCard, NDataTable, NText} from "naive-ui";
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
        const deleteSong = function (id) {
            const api = $route('tools.song.delete.api', id);
            deleteForm.delete(api);
        }

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
                key: 'owner.name',
                render: function (row) {
                    return h(NText, null, {
                        default: () => row.owner.name
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
                                if (row.owner.id === props.accountID) {
                                    return h(NButton, {
                                        onClick: () => redirectToRoute('tools.song.edit', row.id)
                                    }, {
                                        default: () => '编辑'
                                    });
                                }
                            }(),
                            function () {
                                if (row.owner.id === props.accountID) {
                                    return h(NButton, {
                                        type: 'error',
                                        loading: deleteForm.processing,
                                        onClick: () => deleteSong(row.id)
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
            const url = $route('tools.song.list', {
                _query: {
                    page
                }
            });

            updatePageForm.get(url);
        }

        const pagination = {
            page: props.songs.current_page,
            pageSize: props.songs.per_page,
            itemCount: props.songs.total
        }

        return {columns, pagination, updatePage, updatePageForm}
    },
    components: {
        PageLayout,
        NCard,
        NButton,
        NDataTable
    }
}
</script>
