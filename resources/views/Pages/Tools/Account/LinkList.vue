<template>
    <page-layout title="链接列表">
        <n-card>
            <n-data-table
                :columns="columns"
                :data="links.data"
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
import {NButton, NCard, NDataTable, NText} from "naive-ui";
import {h} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "LinkList",
    props: {
        servers: Object,
        links: Object
    },
    setup: function (props) {
        const unlinkForm = useForm(null);

        const columns = [
            {
                title: 'ID',
                key: 'id'
            },
            {
                title: '服务器',
                key: 'server',
                render: function (row) {
                    return h(NText, null, {
                        default: () => props.servers[row.server].alias
                    })
                }
            },
            {
                title: '用户名',
                key: 'target_name'
            },
            {
                title: '账号ID',
                key: 'target_account_id'
            },
            {
                title: '用户ID',
                key: 'target_user_id'
            },
            {
                title: '操作',
                key: 'action',
                render: function (row) {
                    return h(NButton, {
                        type: 'error',
                        loading: unlinkForm.processing,
                        onClick: () => unlinkForm.delete($route('tools.account.unlink.api', row.id))
                    }, {
                        default: () => '解绑'
                    });
                }
            }
        ];

        const updatePageForm = useForm(null);
        const updatePage = function (page) {
            updatePageForm.get($route('tools.account.link.list', {_query: {page}}));
        }

        const pagination = {
            page: props.links.current_page,
            pageSize: props.links.per_page,
            itemCount: props.links.total
        }

        return {columns, pagination, updatePageForm, updatePage}
    },
    components: {
        PageLayout,
        NCard,
        NButton,
        NDataTable
    }
}
</script>
