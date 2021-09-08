<template>
    <page-layout class="lg:w-2/3" title="关卡包列表">
        <n-card>
            <n-button @click="createPackModal.show = true">新建关卡包</n-button>
            <br><br>
            <n-data-table :columns="columns"
                          :data="packs.data"
                          :loading="updatePageForm.processing"
                          :pagination="pagination"
                          remote
                          @update:page="updatePage"/>
        </n-card>

        <n-modal :mask-closable="false" v-model:show="createPackModal.show">
            <n-card class="lg:w-2/3" title="添加关卡包">
                <n-form :model="createPackModal.form">
                    <n-form-item
                        :feedback="createPackModal.form.errors.name ?? null"
                        :validation-status="createPackModal.form.errors.name ? 'error' : null"
                        label="名称"
                        required>
                        <n-input v-model:value="createPackModal.form.name" placeholder="名称"></n-input>
                    </n-form-item>

                    <n-form-item
                        :feedback="createPackModal.form.errors.levels ?? null"
                        :validation-status="createPackModal.form.errors.levels ? 'error' : null"
                        label="关卡"
                        required>
                        <n-dynamic-tags v-model:value="createPackModal.form.levels"></n-dynamic-tags>
                    </n-form-item>

                    <n-form-item
                        :feedback="createPackModal.form.errors.stars ?? null"
                        :validation-status="createPackModal.form.errors.stars ? 'error' : null"
                        label="奖励星星"
                        required>
                        <n-slider :min="1" :step="1"
                                  v-model:value="createPackModal.form.stars"></n-slider>
                    </n-form-item>

                    <n-form-item
                        :feedback="createPackModal.form.errors.coins ?? null"
                        :validation-status="createPackModal.form.errors.coins ? 'error' : null"
                        label="奖励金币"
                        required>
                        <n-slider :min="1" :step="1"
                                  v-model:value="createPackModal.form.coins"></n-slider>
                    </n-form-item>

                    <n-form-item
                        :feedback="createPackModal.form.errors.difficulty ?? null"
                        :validation-status="createPackModal.form.errors.difficulty ? 'error' : null"
                        label="难度"
                        required>
                        <n-slider :format-tooltip="formatDifficulty" :min="0" :max="10" :step="1"
                                  v-model:value="createPackModal.form.difficulty"></n-slider>
                    </n-form-item>

                    <n-form-item
                        :feedback="createPackModal.form.errors.text_color ?? null"
                        :validation-status="createPackModal.form.errors.text_color ? 'error' : null"
                        label="标题颜色"
                        required>
                        <n-color-picker v-model:value="createPackModal.form.text_color"
                                        :show-alpha="false"></n-color-picker>
                    </n-form-item>

                    <n-form-item
                        :feedback="createPackModal.form.errors.bar_color ?? null"
                        :validation-status="createPackModal.form.errors.bar_color ? 'error' : null"
                        label="栏颜色">
                        <n-color-picker v-model:value="createPackModal.form.bar_color"
                                        :show-alpha="false"></n-color-picker>
                    </n-form-item>

                    <n-form-item>
                        <n-space>
                            <n-button @click="createPackModal.show = false">取消</n-button>
                            <n-button
                                :disabled="createPackModal.form.processing"
                                :loading="createPackModal.form.processing"
                                @click="createPackModal.form.put(route('admin.level.pack.create'));">
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
import {useForm} from "@inertiajs/inertia-vue3";
import {h, reactive} from "vue";
import {
    NButton,
    NCard,
    NColorPicker,
    NDataTable,
    NDynamicTags,
    NForm,
    NFormItem,
    NInput,
    NList,
    NListItem,
    NModal,
    NPopconfirm,
    NSelect,
    NSlider,
    NSpace,
    NText
} from "naive-ui";
import {formatTime, redirectToRoute} from "../../../../../js/helper";

export default {
    name: "List",
    props: {
        packs: Object,
        levels: Object
    },
    setup: function (props) {
        const deletePackForm = useForm(null);
        const columns = [
            {
                title: 'ID',
                key: 'id'
            },
            {
                title: '名称',
                key: 'name'
            },
            {
                title: '奖励星星',
                key: 'stars'
            },
            {
                title: '奖励金币',
                key: 'coins'
            },
            {
                title: '难度',
                key: 'difficulty',
                render: function (row) {
                    return h(NText, null, {
                        default: formatDifficulty(row.difficulty)
                    })
                }
            },
            {
                title: '关卡',
                key: 'levels',
                render: function (row) {
                    return h(NList, null, function () {
                        return row.levels.split(',').map(function (level) {
                            return h(NListItem, null, {
                                default: function () {
                                    return h(NListItem, null, {
                                        default: function () {
                                            const name = props.levels[level];
                                            return `${level} - ${name}`;
                                        }
                                    })
                                },
                                suffix: () => h(NButton, {
                                    onClick: () => redirectToRoute('dashboard.level.info', level.id)
                                }, {
                                    default: () => '查看'
                                })
                            })
                        })
                    })
                }
            },
            {
                title: '标题颜色',
                key: 'text_color',
                render: function (row) {
                    if (!row.text_color) {
                        row.text_color = '255,255,255';
                    }

                    return h(NText, {
                        style: `color: rgb(${row.text_color})`
                    }, {
                        default: () => row.text_color
                    })
                }
            },
            {
                title: '栏颜色',
                key: 'bar_color',
                render: function (row) {
                    if (!row.bar_color) {
                        row.bar_color = '255,255,255';
                    }

                    return h(NText, {
                        style: `color: rgb(${row.bar_color})`
                    }, {
                        default: () => row.bar_color
                    })
                }
            },
            {
                title: '创建时间',
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
                    return h(NSpace, null, () => [
                        h(NButton, {
                            onClick: () => redirectToRoute('admin.level.pack.manage', row.id)
                        }, {
                            default: () => '管理'
                        }),
                        h(NPopconfirm, {
                            onPositiveClick: () => deletePackForm.delete($route('admin.level.pack.delete', row.id))
                        }, {
                            default: () => '确认删除?',
                            trigger: () => h(NButton, {
                                loading: deletePackForm.processing,
                                disabled: deletePackForm.processing,
                                type: 'error'
                            }, {
                                default: () => '删除'
                            })
                        })
                    ])
                }
            }
        ]

        const pagination = {
            page: props.packs.current_page,
            pageSize: props.packs.per_page,
            itemCount: props.packs.total
        }

        const createPackModal = reactive({
            show: false,
            form: useForm({
                name: null,
                levels: [],
                stars: null,
                coins: 0,
                difficulty: null,
                text_color: 'rgb(255, 255, 255)',
                bar_color: null
            })
        });

        const formatDifficulty = function (stars) {
            return ['Auto', 'Easy', 'Normal', 'Hard', 'Harder', 'Insane', 'Demon', 'Easy Demon', 'Medium Demon', 'Insane Demon', 'Extreme Demon'][stars] ?? 'Unknown';
        }

        const updatePageForm = useForm(null);
        const updatePage = function (page) {
            updatePageForm.get($route('admin.level.pack.list', {_query: {page}}));
        }

        return {columns, pagination, updatePageForm, updatePage, createPackModal, formatDifficulty}
    },
    components: {
        PageLayout,
        NCard,
        NDataTable,
        NModal,
        NForm,
        NFormItem,
        NInput,
        NSelect,
        NButton,
        NSpace,
        NDynamicTags,
        NSlider,
        NColorPicker
    }
}
</script>
