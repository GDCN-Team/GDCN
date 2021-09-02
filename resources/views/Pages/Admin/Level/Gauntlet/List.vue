<template>
    <page-layout class="lg:w-2/3" title="Gauntlets 列表">
        <n-button @click="createGauntletModal.show = true">新建 Gauntlet</n-button>
        <br><br>
        <n-data-table :columns="columns"
                      :data="gauntlets.data"
                      :loading="updatePageForm.processing"
                      :pagination="pagination"
                      remote
                      @update:page="updatePage"/>

        <n-modal :mask-closable="false" v-model:show="createGauntletModal.show">
            <n-card class="lg:w-1/3" title="添加 Gauntlet">
                <n-form :model="createGauntletModal.form">
                    <n-form-item
                        :feedback="createGauntletModal.form.errors.gauntlet_id ?? null"
                        :validation-status="createGauntletModal.form.errors.gauntlet_id ? 'error' : null"
                        label="类型"
                        required>
                        <n-select v-model:value="createGauntletModal.form.gauntlet_id" :options="gauntlet_types"
                                  placeholder="Gauntlet类型"></n-select>
                    </n-form-item>

                    <n-form-item
                        :feedback="createGauntletModal.form.errors.level1 ?? null"
                        :validation-status="createGauntletModal.form.errors.level1 ? 'error' : null"
                        label="第一关"
                        required>
                        <n-input type="number" v-model:value="createGauntletModal.form.level1"
                                 placeholder="第一关"></n-input>
                    </n-form-item>

                    <n-form-item
                        :feedback="createGauntletModal.form.errors.level2 ?? null"
                        :validation-status="createGauntletModal.form.errors.level2 ? 'error' : null"
                        label="第二关"
                        required>
                        <n-input type="number" v-model:value="createGauntletModal.form.level2"
                                 placeholder="第二关"></n-input>
                    </n-form-item>

                    <n-form-item
                        :feedback="createGauntletModal.form.errors.level3 ?? null"
                        :validation-status="createGauntletModal.form.errors.level3 ? 'error' : null"
                        label="第三关"
                        required>
                        <n-input type="number" v-model:value="createGauntletModal.form.level3"
                                 placeholder="第三关"></n-input>
                    </n-form-item>

                    <n-form-item
                        :feedback="createGauntletModal.form.errors.level4 ?? null"
                        :validation-status="createGauntletModal.form.errors.level4 ? 'error' : null"
                        label="第四关"
                        required>
                        <n-input type="number" v-model:value="createGauntletModal.form.level4"
                                 placeholder="第四关"></n-input>
                    </n-form-item>

                    <n-form-item
                        :feedback="createGauntletModal.form.errors.level5 ?? null"
                        :validation-status="createGauntletModal.form.errors.level5 ? 'error' : null"
                        label="第五关"
                        required>
                        <n-input type="number" v-model:value="createGauntletModal.form.level5"
                                 placeholder="第五关"></n-input>
                    </n-form-item>

                    <n-form-item>
                        <n-space>
                            <n-button @click="createGauntletModal.show = false">取消</n-button>
                            <n-button
                                :disabled="createGauntletModal.form.processing"
                                :loading="createGauntletModal.form.processing"
                                @click="createGauntletModal.form.put(route('admin.level.gauntlet.create'));">
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
import PageLayout from "../../../Components/PageLayout";
import {
    NButton,
    NCard,
    NDataTable,
    NForm,
    NFormItem,
    NInput,
    NList,
    NListItem,
    NModal,
    NPopconfirm,
    NSelect,
    NSpace,
    NText
} from "naive-ui";
import {h, reactive} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {redirectToRoute} from "../../../../../js/helper";

export default {
    name: "List",
    props: {
        gauntlets: Object
    },
    setup: function (props) {
        const deleteLevelGauntletForm = useForm(null);

        const columns = [
            {
                title: 'ID',
                key: 'id'
            },
            {
                title: '类型',
                key: 'gauntlet_id',
                render: function (row) {
                    return h(NText, null, {
                        default: () => [null, "Fire", "Ice", "Poison", "Shadow", "Lava", "Bonus", "Chaos", "Demon", "Time", "Crystal", "Magic", "Spike", "Monster", "Doom", "Death"][row.gauntlet_id] ?? '未知'
                    })
                }
            },
            {
                title: '关卡',
                key: 'levels',
                render: function (row) {
                    return h(NList, null, () => [
                        h(NListItem, null, {
                            default: () => (row.level1?.name ?? '未知') + ' - ' + (row.level1?.user?.name ?? '未知')
                        }),
                        h(NListItem, null, {
                            default: () => (row.level2?.name ?? '未知') + ' - ' + (row.level2?.user?.name ?? '未知')
                        }),
                        h(NListItem, null, {
                            default: () => (row.level3?.name ?? '未知') + ' - ' + (row.level3?.user?.name ?? '未知')
                        }),
                        h(NListItem, null, {
                            default: () => (row.level4?.name ?? '未知') + ' - ' + (row.level4?.user?.name ?? '未知')
                        }),
                        h(NListItem, null, {
                            default: () => (row.level5?.name ?? '未知') + ' - ' + (row.level5?.user?.name ?? '未知')
                        })
                    ])
                }
            },
            {
                title: '操作',
                key: 'action',
                render: function (row) {
                    return h(NSpace, null, () => [
                        h(NButton, {
                            onClick: () => redirectToRoute('admin.level.gauntlet.manage', row.id)
                        }, {
                            default: () => '编辑'
                        }),
                        h(NPopconfirm, {
                            onPositiveClick: () => deleteLevelGauntletForm.delete($route('admin.level.gauntlet.delete', row.id))
                        }, {
                            default: () => '确认删除?',
                            trigger: () => h(NButton, {
                                type: 'error',
                                loading: deleteLevelGauntletForm.processing
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
            updatePageForm.get($route('admin.level.gauntlet.list', {_query: {page}}));
        }

        const pagination = {
            page: props.gauntlets.current_page,
            pageSize: props.gauntlets.per_page,
            itemCount: props.gauntlets.total
        }

        const createGauntletModal = reactive({
            show: false,
            form: useForm({
                gauntlet_id: null,
                level1: null,
                level2: null,
                level3: null,
                level4: null,
                level5: null
            })
        });

        const gauntlet_types = [
            {
                label: "Fire",
                value: 1
            }, {
                label: "Ice",
                value: 2
            }, {
                label: "Poison",
                value: 3
            }, {
                label: "Shadow",
                value: 4
            }, {
                label: "Lava",
                value: 5
            }, {
                label: "Bonus",
                value: 6
            }, {
                label: "Chaos",
                value: 7
            }, {
                label: "Demon",
                value: 8
            }, {
                label: "Time",
                value: 9
            }, {
                label: "Crystal",
                value: 10
            }, {
                label: "Magic",
                value: 11
            }, {
                label: "Spike",
                value: 12
            }, {
                label: "Monster",
                value: 13
            }, {
                label: "Doom",
                value: 14
            }, {
                label: "Death",
                value: 15
            }
        ]

        return {
            columns,
            updatePageForm,
            updatePage,
            pagination,
            createGauntletModal,
            gauntlet_types
        }
    },
    components: {
        PageLayout,
        NDataTable,
        NModal,
        NCard,
        NButton,
        NForm,
        NFormItem,
        NInput,
        NSelect,
        NSpace
    }
}
</script>
