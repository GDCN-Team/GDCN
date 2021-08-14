<template>
    <page-layout class="lg:w-1/3" title="链接列表">
        <n-card>
            <n-data-table
                remote
                :columns="columns"
                :data="links.data"
                :pagination="pagination"
                :loading="updatePageForm.processing"
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
        const unlink = function (id) {
            const api = $route('tools.account.unlink.api', id);
            unlinkForm.delete(api);
        }

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
                        onClick: () => unlink(row.id),
                    }, {
                        default: () => '解绑'
                    });
                }
            }
        ];

        const updatePageForm = useForm(null);
        const updatePage = function (page) {
            const url = $route('tools.account.link.list', {
                _query: {
                    page
                }
            });

            updatePageForm.get(url);
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
