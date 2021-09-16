<template>
    <page-layout class="lg:w-2/3" title="Gauntlet 管理">
        <n-card>
            <n-form :model="updateGauntletForm">
                <n-descriptions :columns="columns" bordered title="Gauntlet 管理">
                    <n-descriptions-item label="ID">
                        {{ gauntlet.id }}
                    </n-descriptions-item>
                    <n-descriptions-item label="类型">
                        <n-form-item
                            :feedback="updateGauntletForm.errors.type ?? null"
                            :validation-status="updateGauntletForm.errors.type ? 'error' : null"
                            required>
                            <n-select v-model:value="updateGauntletForm.type" :options="gauntlet_types"
                                      placeholder="Gauntlet类型"></n-select>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="第一关">
                        <n-space justify="space-between">
                            <n-form-item
                                :feedback="updateGauntletForm.errors.level1 ?? null"
                                :validation-status="updateGauntletForm.errors.level1 ? 'error' : null"
                                required>
                                <n-space vertical>
                                    <n-button text type="primary"
                                              @click="redirectToRoute('dashboard.level.info', gauntlet.level1?.id)">{{
                                            (gauntlet.level1?.id ?? '未知') + ' - ' + (gauntlet.level1?.name ?? '未知')
                                        }}
                                    </n-button>

                                    <n-form-item
                                        :feedback="updateGauntletForm.errors.level1 ?? null"
                                        :validation-status="updateGauntletForm.errors.level1 ? 'error' : null"
                                        required>
                                        <n-input v-model:value="updateGauntletForm.level1" placeholder="第一关"></n-input>
                                    </n-form-item>
                                </n-space>
                            </n-form-item>
                        </n-space>
                    </n-descriptions-item>
                    <n-descriptions-item label="第二关">
                        <n-space justify="space-between">
                            <n-space vertical>
                                <n-button text type="primary"
                                          @click="redirectToRoute('dashboard.level.info', gauntlet.level2?.id)">{{
                                        (gauntlet.level2?.id ?? '未知') + ' - ' + (gauntlet.level2?.name ?? '未知')
                                    }}
                                </n-button>

                                <n-form-item
                                    :feedback="updateGauntletForm.errors.level2 ?? null"
                                    :validation-status="updateGauntletForm.errors.level2 ? 'error' : null"
                                    required>
                                    <n-input v-model:value="updateGauntletForm.level2" placeholder="第二关"></n-input>
                                </n-form-item>
                            </n-space>
                        </n-space>
                    </n-descriptions-item>
                    <n-descriptions-item label="第三关">
                        <n-space justify="space-between">
                            <n-space vertical>
                                <n-button text type="primary"
                                          @click="redirectToRoute('dashboard.level.info', gauntlet.level3?.id)">{{
                                        (gauntlet.level3?.id ?? '未知') + ' - ' + (gauntlet.level3?.name ?? '未知')
                                    }}
                                </n-button>

                                <n-form-item
                                    :feedback="updateGauntletForm.errors.level3 ?? null"
                                    :validation-status="updateGauntletForm.errors.level3 ? 'error' : null"
                                    required>
                                    <n-input v-model:value="updateGauntletForm.level3" placeholder="第三关"></n-input>
                                </n-form-item>
                            </n-space>
                        </n-space>
                    </n-descriptions-item>
                    <n-descriptions-item label="第四关">
                        <n-space justify="space-between">
                            <n-space vertical>
                                <n-button text type="primary"
                                          @click="redirectToRoute('dashboard.level.info', gauntlet.level4?.id)">{{
                                        (gauntlet.level4?.id ?? '未知') + ' - ' + (gauntlet.level4?.name ?? '未知')
                                    }}
                                </n-button>

                                <n-form-item
                                    :feedback="updateGauntletForm.errors.level4 ?? null"
                                    :validation-status="updateGauntletForm.errors.level4 ? 'error' : null"
                                    required>
                                    <n-input v-model:value="updateGauntletForm.level4" placeholder="第四关"></n-input>
                                </n-form-item>
                            </n-space>
                        </n-space>
                    </n-descriptions-item>
                    <n-descriptions-item label="第五关">
                        <n-space justify="space-between">
                            <n-space vertical>
                                <n-button text type="primary"
                                          @click="redirectToRoute('dashboard.level.info', gauntlet.level5?.id)">{{
                                        (gauntlet.level5?.id ?? '未知') + ' - ' + (gauntlet.level5?.name ?? '未知')
                                    }}
                                </n-button>

                                <n-form-item
                                    :feedback="updateGauntletForm.errors.level5 ?? null"
                                    :validation-status="updateGauntletForm.errors.level5 ? 'error' : null"
                                    required>
                                    <n-input v-model:value="updateGauntletForm.level5" placeholder="第五关"></n-input>
                                </n-form-item>
                            </n-space>
                        </n-space>
                    </n-descriptions-item>
                    <n-descriptions-item label="创建时间">
                        {{ formatTime(gauntlet.created_at, '未知') }}
                    </n-descriptions-item>
                    <n-descriptions-item label="最后编辑时间">
                        {{ formatTime(gauntlet.updated_at, '无') }}
                    </n-descriptions-item>
                </n-descriptions>
                <br>
                <n-space>
                    <n-button @click="updateGauntletForm.reset()">取消</n-button>
                    <n-button :disabled="updateGauntletForm.processing || !updateGauntletForm.isDirty"
                              :loading="updateGauntletForm.processing"
                              @click="updateGauntletForm.patch(route('admin.level.gauntlet.update', gauntlet.id))">更新
                    </n-button>
                </n-space>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../../../Components/PageLayout";
import {
    NButton,
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NForm,
    NFormItem,
    NInput,
    NSelect,
    NSpace,
    NText
} from "naive-ui";
import {formatTime, isMobile, redirectToRoute} from "../../../../../js/helper";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "Manage",
    props: {
        gauntlet: Object
    },
    computed: {
        columns: function () {
            return isMobile() ? 3 : 1;
        }
    },
    setup: function (props) {
        const updateGauntletForm = useForm({
            type: props.gauntlet.gauntlet_id,
            level1: props.gauntlet.level1.id.toString(),
            level2: props.gauntlet.level2.id.toString(),
            level3: props.gauntlet.level3.id.toString(),
            level4: props.gauntlet.level4.id.toString(),
            level5: props.gauntlet.level5.id.toString()
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

        return {formatTime, redirectToRoute, updateGauntletForm, gauntlet_types}
    },
    components: {
        PageLayout,
        NCard,
        NDescriptions,
        NDescriptionsItem,
        NSpace,
        NText,
        NButton,
        NForm,
        NFormItem,
        NInput,
        NSelect
    }
}
</script>
