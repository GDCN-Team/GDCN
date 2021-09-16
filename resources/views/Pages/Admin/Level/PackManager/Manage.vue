<template>
    <page-layout class="lg:w-2/3" title="关卡包管理">
        <n-card>
            <n-form :model="updatePackForm">
                <n-descriptions :columns="columns" :title="pack.name" bordered>
                    <n-descriptions-item label="ID">
                        {{ pack.id }}
                    </n-descriptions-item>
                    <n-descriptions-item label="名称">
                        <n-form-item
                            :feedback="updatePackForm.errors.name ?? null"
                            :validation-status="updatePackForm.errors.name ? 'error' : null"
                            required>
                            <n-input v-model:value="updatePackForm.name" placeholder="名称"></n-input>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="关卡">
                        <n-space>
                            <n-space vertical>
                                <n-button text type="primary"
                                          v-for="level in pack.levels.split(',')"
                                          @click="redirectToRoute('dashboard.level.info', level)">
                                    {{ level }} - {{ levels[level] }}
                                </n-button>
                            </n-space>

                            <n-form-item
                                :feedback="updatePackForm.errors.levels ?? null"
                                :validation-status="updatePackForm.errors.levels ? 'error' : null"
                                label="关卡"
                                required>
                                <n-dynamic-tags v-model:value="updatePackForm.levels"></n-dynamic-tags>
                            </n-form-item>
                        </n-space>
                    </n-descriptions-item>
                    <n-descriptions-item label="奖励星星">
                        <n-form-item
                            :feedback="updatePackForm.errors.stars ?? null"
                            :validation-status="updatePackForm.errors.stars ? 'error' : null"
                            label="奖励星星"
                            required>
                            <n-slider :min="1" :step="1"
                                      v-model:value="updatePackForm.stars"></n-slider>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="奖励金币">
                        <n-form-item
                            :feedback="updatePackForm.errors.coins ?? null"
                            :validation-status="updatePackForm.errors.coins ? 'error' : null"
                            label="奖励金币"
                            required>
                            <n-slider :min="1" :step="1"
                                      v-model:value="updatePackForm.coins"></n-slider>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="难度">
                        <n-form-item
                            :feedback="updatePackForm.errors.difficulty ?? null"
                            :validation-status="updatePackForm.errors.difficulty ? 'error' : null"
                            label="难度"
                            required>
                            <n-slider :format-tooltip="formatDifficulty" :min="0" :max="10" :step="1"
                                      v-model:value="updatePackForm.difficulty"></n-slider>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="标题颜色">
                        <n-form-item
                            :feedback="updatePackForm.errors.text_color ?? null"
                            :validation-status="updatePackForm.errors.text_color ? 'error' : null"
                            label="标题颜色"
                            required>
                            <n-color-picker v-model:value="updatePackForm.text_color"
                                            :show-alpha="false"></n-color-picker>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="栏颜色">
                        <n-form-item
                            :feedback="updatePackForm.errors.bar_color ?? null"
                            :validation-status="updatePackForm.errors.bar_color ? 'error' : null"
                            label="栏颜色"
                            required>
                            <n-color-picker v-model:value="updatePackForm.bar_color"
                                            :show-alpha="false"></n-color-picker>
                        </n-form-item>
                    </n-descriptions-item>
                    <n-descriptions-item label="创建时间">
                        {{ formatTime(pack.created_at, '未知') }}
                    </n-descriptions-item>
                    <n-descriptions-item label="最后编辑时间">
                        {{ formatTime(pack.updated_at, '无') }}
                    </n-descriptions-item>
                </n-descriptions>
                <br>
                <n-space>
                    <n-button @click="updatePackForm.reset()">取消</n-button>
                    <n-button :loading="updatePackForm.processing"
                              :disabled="updatePackForm.processing || !updatePackForm.isDirty"
                              @click="updatePackForm.patch(route('admin.level.pack.update', pack.id))">更新
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
    NColorPicker,
    NDescriptions,
    NDescriptionsItem,
    NDynamicTags,
    NForm,
    NFormItem,
    NInput,
    NSlider,
    NSpace
} from "naive-ui";
import {formatTime, isMobile, redirectToRoute} from "../../../../../js/helper";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "Manage",
    props: {
        pack: Object,
        levels: Object
    },
    computed: {
        columns: function () {
            return isMobile() ? 3 : 1;
        }
    },
    setup: function (props) {
        const updatePackForm = useForm({
            name: props.pack.name,
            levels: props.pack.levels.split(","),
            stars: props.pack.stars,
            coins: props.pack.coins,
            difficulty: props.pack.difficulty,
            text_color: `rgb(${props.pack.text_color.split(',').join(', ')})`,
            bar_color: `rgb(${(props.pack.bar_color || '255,255,255').split(',').join(', ')})`
        });

        const formatDifficulty = function (stars) {
            return ['Auto', 'Easy', 'Normal', 'Hard', 'Harder', 'Insane', 'Demon', 'Easy Demon', 'Medium Demon', 'Insane Demon', 'Extreme Demon'][stars] ?? 'Unknown';
        }

        return {formatDifficulty, formatTime, updatePackForm, redirectToRoute};
    },
    components: {
        PageLayout,
        NCard,
        NDescriptions,
        NDescriptionsItem,
        NButton,
        NForm,
        NFormItem,
        NInput,
        NDynamicTags,
        NSlider,
        NColorPicker,
        NSpace
    }
}
</script>
