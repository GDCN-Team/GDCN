<template>
    <page-layout class="lg:w-2/3" title="权限组管理">
        <n-card>
            <n-descriptions :columns="columns" bordered :title="group.name">
                <n-descriptions-item label="ID">
                    {{ group.id }}
                </n-descriptions-item>
                <n-descriptions-item label="名称">
                    {{ group.name }}
                </n-descriptions-item>
                <n-descriptions-item label="Mod等级">
                    {{ mod_level_name }}
                </n-descriptions-item>
                <n-descriptions-item label="评论颜色">
                    <span :style="{ color: `rgb(${group.comment_color})` }">{{ group.comment_color }}</span>
                </n-descriptions-item>
                <n-descriptions-item label="创建时间">
                    {{ formatTime(group.created_at, '未知') }}
                </n-descriptions-item>
                <n-descriptions-item label="更新时间">
                    {{ formatTime(group.updated_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>
            <n-grid x-gap="10" y-gap="10" cols="1 768:2" class="mt-5">
                <n-grid-item>
                    <n-h4>
                        成员
                        <n-button text @click="modalShow.addMember = true">
                            <n-icon :size="20">
                                <Add></Add>
                            </n-icon>
                        </n-button>
                    </n-h4>

                    <n-list bordered>
                        <n-list-item v-for="account in group.members">
                            {{ account.name }}

                            <template #suffix>
                                <n-button :loading="removeAccountFromGroupForm.processing" type="error"
                                          @click="removeAccountFromGroupForm.delete(route('admin.group.manage.delete.member', [group.id, account.id]))">
                                    移除
                                </n-button>
                            </template>
                        </n-list-item>
                    </n-list>
                </n-grid-item>
                <n-grid-item>
                    <n-h4>
                        权限
                        <n-button text @click="modalShow.addFlag = true">
                            <n-icon :size="20">
                                <Add></Add>
                            </n-icon>
                        </n-button>
                    </n-h4>

                    <n-list bordered>
                        <n-list-item v-for="flag in group.flags">
                            {{ flag.name }}

                            <template #suffix>
                                <n-button :loading="removeFlagFromGroupForm.processing" type="error"
                                          @click="removeFlagFromGroupForm.delete(route('admin.group.manage.delete.flag', [group.id, flag.id]))">
                                    移除
                                </n-button>
                            </template>
                        </n-list-item>
                    </n-list>
                </n-grid-item>
            </n-grid>
        </n-card>

        <n-modal v-model:show="modalShow.addMember">
            <n-card title="添加成员" class="lg:w-1/3">
                <n-form inline :model="searchAccountForm">
                    <n-form-item
                        required
                        :validation-status="searchAccountForm.errors.accountSearchText ? 'error' : null"
                        :feedback="searchAccountForm.errors.accountSearchText ?? null"
                        path="searchAccountForm.accountSearchText"
                        label="用户名">
                        <n-input v-model:value="searchAccountForm.accountSearchText" placeholder="用户名"></n-input>
                    </n-form-item>

                    <n-form-item>
                        <n-button
                            :loading="searchAccountForm.processing"
                            :disabled="searchAccountForm.processing"
                            @click="searchAccountForm.get(route('admin.group.manage', group.id), { only: ['accounts'], preserveState: true });">
                            搜索
                        </n-button>
                    </n-form-item>
                </n-form>
                <n-data-table
                    remote
                    :columns="accounts_search_table.columns"
                    :data="accounts?.data"
                    :pagination="accounts_search_table.pagination"
                    :loading="accounts_search_table.updatePageForm.processing"
                    @update:page="accounts_search_table.updatePage"
                ></n-data-table>
            </n-card>
        </n-modal>

        <n-modal v-model:show="modalShow.addFlag">
            <n-card title="添加权限" class="lg:w-1/3">
                <n-form inline :model="searchFlagForm">
                    <n-form-item
                        required
                        :validation-status="searchFlagForm.errors.flagSearchText ? 'error' : null"
                        :feedback="searchFlagForm.errors.flagSearchText ?? null"
                        path="searchFlagForm.flagSearchText"
                        label="权限名">
                        <n-input v-model:value="searchFlagForm.flagSearchText" placeholder="权限名"></n-input>
                    </n-form-item>

                    <n-form-item>
                        <n-button
                            :loading="searchFlagForm.processing"
                            :disabled="searchFlagForm.processing"
                            @click="searchFlagForm.get(route('admin.group.manage', group.id), { only: ['flags'], preserveState: true });">
                            搜索
                        </n-button>
                    </n-form-item>
                </n-form>
                <n-data-table
                    remote
                    :columns="flags_search_table.columns"
                    :data="flags?.data"
                    :pagination="flags_search_table.pagination"
                    :loading="flags_search_table.updatePageForm.processing"
                    @update:page="flags_search_table.updatePage"
                ></n-data-table>
            </n-card>
        </n-modal>
    </page-layout>
</template>

<script>
import PageLayout from "../../Components/PageLayout";
import {
    NButton,
    NCard,
    NDataTable,
    NDescriptions,
    NDescriptionsItem,
    NForm,
    NFormItem,
    NGrid,
    NGridItem,
    NH4,
    NIcon,
    NInput,
    NList,
    NListItem,
    NModal
} from "naive-ui";
import {formatTime, isMobile} from "../../../../js/helper";
import {Add} from "@vicons/carbon";
import {computed, h, reactive} from "vue";
import {useForm, usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "Manage",
    props: {
        group: Object
    },
    computed: {
        columns: function () {
            return isMobile() ? 3 : 1;
        },
        mod_level_name: function (app) {
            switch (app.group.mod_level) {
                case 1:
                    return 'Normal Mod';
                case 2:
                    return 'Elder Mod';
                default:
                    return 'Unknown'
            }
        }
    },
    setup: function (props) {
        const accounts = computed(function () {
            const $page = usePage();
            const accounts = $page.props.value.accounts;

            accounts_search_table.pagination = {
                page: accounts?.current_page,
                pageSize: accounts?.per_page,
                itemCount: accounts?.total
            }

            return accounts;
        });

        const flags = computed(function () {
            const $page = usePage();
            const flags = $page.props.value.flags;

            flags_search_table.pagination = {
                page: flags?.current_page,
                pageSize: flags?.per_page,
                itemCount: flags?.total
            }

            return flags;
        });

        const modalShow = reactive({
            addMember: false,
            addFlag: false
        });

        const searchAccountForm = useForm({
            accountSearchText: null
        });

        const searchFlagForm = useForm({
            flagSearchText: null
        });

        const accounts_search_table = {
            columns: [
                {
                    title: '账号ID',
                    key: 'id'
                },
                {
                    title: '名称',
                    key: 'name'
                },
                {
                    title: '操作',
                    key: 'action',
                    render: function (row) {
                        return h(NButton, {
                            type: 'success',
                            loading: accounts_search_table.addForm.processing,
                            onClick: () => accounts_search_table.addForm.put($route('admin.group.manage.add.member', [props.group.id, row.id]), {
                                preserveState: false
                            })
                        }, {
                            default: () => '添加'
                        })
                    }
                }
            ],
            pagination: {
                page: accounts?.current_page,
                pageSize: accounts?.per_page,
                itemCount: accounts?.total
            },
            addForm: useForm(null),
            updatePageForm: useForm(null),
            updatePage: page => accounts_search_table.updatePageForm.get($route('admin.group.manage', {
                group: props.group.id,
                _query: {
                    accountSearchText: searchAccountForm.accountSearchText,
                    page
                }
            }), {
                only: ['accounts'],
                preserveState: true
            })
        };

        const flags_search_table = {
            columns: [
                {
                    title: '权限ID',
                    key: 'id'
                },
                {
                    title: '名称',
                    key: 'name'
                },
                {
                    title: '操作',
                    key: 'action',
                    render: function (row) {
                        return h(NButton, {
                            type: 'success',
                            loading: flags_search_table.addForm.processing,
                            onClick: () => flags_search_table.addForm.put($route('admin.group.manage.add.flag', [props.group.id, row.id]), {
                                preserveState: false
                            })
                        }, {
                            default: () => '添加'
                        })
                    }
                }
            ],
            pagination: {
                page: flags?.current_page,
                pageSize: flags?.per_page,
                itemCount: flags?.total
            },
            addForm: useForm(null),
            updatePageForm: useForm(null),
            updatePage: page => flags_search_table.updatePageForm.get($route('admin.group.manage', {
                group: props.group.id,
                _query: {
                    flagSearchText: searchFlagForm.flagSearchText,
                    page
                }
            }), {
                only: ['flags'],
                preserveState: true
            })
        };

        const addFlagToGroupForm = useForm({
            name: null
        });

        const removeAccountFromGroupForm = useForm(null);
        const removeFlagFromGroupForm = useForm(null);

        return {
            formatTime,
            modalShow,
            searchAccountForm,
            accounts_search_table,
            accounts,
            addFlagToGroupForm,
            removeAccountFromGroupForm,
            removeFlagFromGroupForm,
            searchFlagForm,
            flags_search_table,
            flags
        }
    },
    components: {
        PageLayout,
        NCard,
        NDescriptions,
        NDescriptionsItem,
        NGrid,
        NGridItem,
        NH4,
        NIcon,
        Add,
        NButton,
        NModal,
        NInput,
        NForm,
        NFormItem,
        NDataTable,
        NList,
        NListItem
    }
}
</script>
