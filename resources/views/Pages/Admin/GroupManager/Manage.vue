<template>
    <page-layout class="lg:w-2/3" title="权限组管理">
        <n-card>
            <n-form :model="updateGroupForm">
                <n-descriptions :columns="columns" :title="group.name" bordered>
                    <n-descriptions-item label="ID">
                        {{ group.id }}
                    </n-descriptions-item>
                    <n-descriptions-item label="名称">
                        <n-form-item
                            :feedback="updateGroupForm.errors.name ?? null"
                            :validation-status="updateGroupForm.errors.name ? 'error' : null"
                            required>
                            <n-input v-model:value="updateGroupForm.name" placeholder="名称"></n-input>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="Mod等级">
                        <n-form-item
                            :feedback="updateGroupForm.errors.mod_level ?? null"
                            :validation-status="updateGroupForm.errors.mod_level ? 'error' : null"
                            required>
                            <n-select v-model:value="updateGroupForm.mod_level" :options="mod_level_aliases"
                                      placeholder="Mod等级"></n-select>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="评论颜色">
                        <n-form-item
                            :feedback="updateGroupForm.errors.comment_color ?? null"
                            :validation-status="updateGroupForm.errors.comment_color ? 'error' : null"
                            required>
                            <n-color-picker :show-alpha="false"
                                            v-model:value="updateGroupForm.comment_color"></n-color-picker>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="创建时间">
                        {{ formatTime(group.created_at, '未知') }}
                    </n-descriptions-item>
                    <n-descriptions-item label="最后编辑时间">
                        {{ formatTime(group.updated_at, '无') }}
                    </n-descriptions-item>
                </n-descriptions>
                <br>
                <n-space>
                    <n-button @click="updateGroupForm.reset()">取消</n-button>
                    <n-button :loading="updateGroupForm.processing"
                              :disabled="updateGroupForm.processing || !updateGroupForm.isDirty"
                              @click="updateGroupForm.patch(route('admin.group.update', group.id))">更新
                    </n-button>
                </n-space>
            </n-form>
            <n-grid class="mt-5" cols="1 768:2" x-gap="10" y-gap="10">
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
                                          :disabled="removeAccountFromGroupForm.processing"
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
                                          :disabled="removeFlagFromGroupForm.processing"
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
            <n-card class="lg:w-2/3" title="添加成员">
                <n-form :model="searchAccountForm" inline>
                    <n-form-item
                        :feedback="searchAccountForm.errors.accountSearchText ?? null"
                        :validation-status="searchAccountForm.errors.accountSearchText ? 'error' : null"
                        label="用户名"
                        required>
                        <n-input v-model:value="searchAccountForm.accountSearchText" placeholder="用户名"></n-input>
                    </n-form-item>

                    <n-form-item>
                        <n-button
                            :disabled="searchAccountForm.processing"
                            :loading="searchAccountForm.processing"
                            @click="searchAccountForm.get(route('admin.group.manage', group.id), { only: ['accounts'], preserveState: true });">
                            搜索
                        </n-button>
                    </n-form-item>
                </n-form>
                <n-data-table
                    :columns="accounts_search_table.columns"
                    :data="accounts?.data"
                    :loading="accounts_search_table.updatePageForm.processing"
                    :pagination="accounts_search_table.pagination"
                    remote
                    @update:page="accounts_search_table.updatePage"
                ></n-data-table>
            </n-card>
        </n-modal>

        <n-modal v-model:show="modalShow.addFlag">
            <n-card class="lg:w-2/3" title="添加权限">
                <n-form :model="searchFlagForm" inline>
                    <n-form-item
                        :feedback="searchFlagForm.errors.flagSearchText ?? null"
                        :validation-status="searchFlagForm.errors.flagSearchText ? 'error' : null"
                        label="权限名"
                        required>
                        <n-input v-model:value="searchFlagForm.flagSearchText" placeholder="权限名"></n-input>
                    </n-form-item>

                    <n-form-item>
                        <n-button
                            :disabled="searchFlagForm.processing"
                            :loading="searchFlagForm.processing"
                            @click="searchFlagForm.get(route('admin.group.manage', group.id), { only: ['flags'], preserveState: true });">
                            搜索
                        </n-button>
                    </n-form-item>
                </n-form>
                <n-data-table
                    :columns="flags_search_table.columns"
                    :data="flags?.data"
                    :loading="flags_search_table.updatePageForm.processing"
                    :pagination="flags_search_table.pagination"
                    remote
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
    NColorPicker,
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
    NModal,
    NSelect,
    NSpace
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
        const updateGroupForm = useForm({
            name: props.group.name,
            mod_level: props.group.mod_level,
            comment_color: `rgb(${props.group.comment_color})`
        });

        const mod_level_aliases = [
            {
                label: 'Player',
                value: 0
            },
            {
                label: 'Normal Mod',
                value: 1
            },
            {
                label: 'Elder Mod',
                value: 2
            }
        ];

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
            flags,
            updateGroupForm,
            mod_level_aliases
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
        NSelect,
        NColorPicker,
        NSpace,
        NList,
        NListItem
    }
}
</script>
