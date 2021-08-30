<template>
    <page-layout class="lg:w-1/3" title="权限组列表">
        <n-card>
            <n-data-table remote
                          :columns="columns"
                          :data="groups.data"
                          :loading="updatePageForm.processing"
                          @update:page="updatePage"
                          :pagination="pagination"/>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {NCard, NDataTable, NText} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import {h} from "vue";

export default {
    name: "List",
    props: {
        groups: Object
    },
    setup: function (props) {
        const columns = [
            {
                title: '组ID',
                key: 'id'
            },
            {
                title: '组名',
                key: 'name'
            },
            {
                title: 'Mod等级',
                key: 'mod_level',
                render: function (row) {
                    return h(NText, null, {
                        default: function () {
                            switch (row.mod_level) {
                                case 1:
                                    return 'Normal Mod';
                                case 2:
                                    return 'Elder Mod';
                                default:
                                    return 'Unknown'
                            }
                        }
                    })
                }
            },
            {
                title: '评论颜色',
                key: 'comment_color',
                render: function (row) {
                    return h('div', {
                        style: `color: rgb(${row.comment_color})`
                    }, {
                        default: () => row.comment_color
                    })
                }
            }
        ];

        const updatePageForm = useForm(null);
        const updatePage = function (page) {
            updatePageForm.get($route('admin.group.list', {_query: {page}}));
        }

        const pagination = {
            page: props.groups.current_page,
            pageSize: props.groups.per_page,
            itemCount: props.groups.total
        }

        return {columns, pagination, updatePage, updatePageForm};
    },
    components: {
        PageLayout,
        NCard,
        NDataTable
    }
}
</script>
