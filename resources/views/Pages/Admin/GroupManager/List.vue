<template>
    <page-layout class="lg:w-2/3" title="权限组列表">
        <n-card>
            <n-button @click="createGroupModel.show = true">新建权限组</n-button>
            <br><br>
            <n-data-table :columns="columns"
                          :data="groups.data"
                          :loading="updatePageForm.processing"
                          :pagination="pagination"
                          remote
                          @update:page="updatePage"/>
        </n-card>

        <n-modal :mask-closable="false" v-model:show="createGroupModel.show">
            <n-card class="lg:w-2/3" title="添加组">
                <n-form :model="createGroupModel.form">
                    <n-form-item
                        :feedback="createGroupModel.form.errors.name ?? null"
                        :validation-status="createGroupModel.form.errors.name ? 'error' : null"
                        label="名称"
                        required>
                        <n-input v-model:value="createGroupModel.form.name" placeholder="用户名"></n-input>
                    </n-form-item>

                    <n-form-item
                        :feedback="createGroupModel.form.errors.mod_level ?? null"
                        :validation-status="createGroupModel.form.errors.mod_level ? 'error' : null"
                        label="Mod等级"
                        required>
                        <n-select v-model:value="createGroupModel.form.mod_level" :options="mod_level_aliases"
                                  placeholder="Mod等级"></n-select>
                    </n-form-item>

                    <n-form-item
                        :feedback="createGroupModel.form.errors.comment_color ?? null"
                        :validation-status="createGroupModel.form.errors.comment_color ? 'error' : null"
                        label="评论颜色"
                        required>
                        <n-color-picker :show-alpha="false"
                                        v-model:value="createGroupModel.form.comment_color"></n-color-picker>
                    </n-form-item>

                    <n-form-item>
                        <n-space>
                            <n-button @click="createGroupModel.show = false">取消</n-button>
                            <n-button
                                :disabled="createGroupModel.form.processing"
                                :loading="createGroupModel.form.processing"
                                @click="createGroupModel.form.put(route('admin.group.create'));">
                                添加
                            </n-button>
                        </n-space>
                    </n-form-item>
                </n-form>
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
    NForm,
    NFormItem,
    NInput,
    NModal,
    NPopconfirm,
    NSelect,
    NSpace,
    NText
} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import {h, reactive} from "vue";
import {redirectToRoute} from "../../../../js/helper";

export default {
    name: "List",
    props: {
        groups: Object
    },
    setup: function (props) {
        const deleteGroupForm = useForm(null);

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
                                case 0:
                                    return 'Player';
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
            },
            {
                title: '操作',
                key: 'action',
                render: function (row) {
                    return h(NSpace, null, () => [
                        h(NButton, {
                            onClick: () => redirectToRoute('admin.group.manage', row.id)
                        }, {
                            default: () => '编辑'
                        }),
                        h(NPopconfirm, {
                            onPositiveClick: () => deleteGroupForm.delete($route('admin.group.delete', row.id))
                        }, {
                            default: () => '确认删除?',
                            trigger: () => h(NButton, {
                                type: 'error',
                                loading: deleteGroupForm.processing
                            }, {
                                default: () => '删除'
                            })
                        })
                    ])
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

        const createGroupModel = reactive({
            show: false,
            form: useForm({
                name: null,
                mod_level: null,
                comment_color: null
            })
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

        return {columns, pagination, updatePage, updatePageForm, createGroupModel, mod_level_aliases};
    },
    components: {
        PageLayout,
        NCard,
        NDataTable,
        NButton,
        NSelect,
        NModal,
        NForm,
        NFormItem,
        NInput,
        NColorPicker,
        NSpace
    }
}
</script>
