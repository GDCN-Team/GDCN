<template>
    <page-layout class="lg:w-2/3" title="个人资料">
        <n-card class="mt-5">
            <n-grid x-gap="10" y-gap="10" cols="1 768:2">
                <n-grid-item>
                    <div v-if="account">
                        <n-descriptions :columns="columns" bordered title="账号信息">
                            <n-descriptions-item label="账号ID">
                                {{ account.id }}
                            </n-descriptions-item>
                            <n-descriptions-item label="用户名">
                                {{ account.name }}
                            </n-descriptions-item>
                            <n-descriptions-item label="邮箱">
                                {{ account.email }}
                            </n-descriptions-item>
                            <n-descriptions-item label="注册时间">
                                {{ formatTime(account.created_at, '未知') }}
                            </n-descriptions-item>
                            <n-descriptions-item label="邮箱验证时间">
                                {{ formatTime(account.email_verified_at, '无') }}
                            </n-descriptions-item>
                        </n-descriptions>
                    </div>

                    <span class="text-red-700 font-bold text-lg" v-else>载入失败</span>
                </n-grid-item>

                <n-grid-item>
                    <div v-if="account.user">
                        <n-descriptions :columns="columns" bordered title="用户信息">
                            <n-descriptions-item label="用户ID">
                                {{ account.user.id }}
                            </n-descriptions-item>
                            <n-descriptions-item label="用户名">
                                {{ account.user.name }}
                                <n-button type="primary" text
                                          :loading="syncUserNameForm.processing"
                                          :disabled="syncUserNameForm.processing || account.name === account.user.name"
                                          @click="syncUserNameForm.get(route('dashboard.profile.name.sync.api'))">
                                    (同步)
                                </n-button>
                            </n-descriptions-item>
                            <n-descriptions-item label="用户唯一识别码">
                                        <span v-if="visible.uuid">
                                            {{ account.user.uuid }}
                                            <n-button type="primary" text
                                                      @click="invertValue(visible, 'uuid')">(隐藏)</n-button>
                                        </span>

                                <n-button type="primary" v-else text @click="invertValue(visible, 'uuid')">
                                    显示
                                </n-button>
                            </n-descriptions-item>
                            <n-descriptions-item label="设备唯一识别码">
                                        <span v-if="visible.udid">
                                            {{ account.user.udid }}
                                            <n-button type="primary" text
                                                      @click="invertValue(visible, 'udid')">(隐藏)</n-button>
                                        </span>

                                <n-button type="primary" v-else text @click="invertValue(visible, 'udid')">
                                    显示
                                </n-button>
                            </n-descriptions-item>
                            <n-descriptions-item label="创建时间">
                                {{ formatTime(account.user.created_at, '未知') }}
                            </n-descriptions-item>
                        </n-descriptions>
                    </div>

                    <span class="text-red-700 font-bold text-lg" v-else>载入失败</span>
                </n-grid-item>
            </n-grid>

            <template #action>
                <n-space justify="space-between">
                    <n-space justify="left">
                        <n-button @click="redirectToRoute('dashboard.profile.setting')">账号设置</n-button>
                        <n-button :loading="logoutForm.processing" :disabled="logoutForm.processing"
                                  @click="logoutForm.post(route('auth.logout.api'))">
                            登出
                        </n-button>
                    </n-space>

                    <n-button
                        :loading="resendVerificationEmailForm.processing"
                        :disabled="resendVerificationEmailForm.processing || account.email_verified_at !== null"
                        @click="resendVerificationEmailForm.get(route('dashboard.profile.verification.email.resend.api'))">
                        重发验证邮件
                    </n-button>
                </n-space>
            </template>
        </n-card>
    </page-layout>
</template>

<script>
import {NButton, NCard, NDescriptions, NDescriptionsItem, NGrid, NGridItem, NSpace} from "naive-ui";
import {formatTime, invertValue, isMobile, redirectToRoute} from "../../../js/helper";
import {reactive} from "vue";
import PageLayout from "../Components/PageLayout";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "Profile",
    props: {
        account: Object
    },
    computed: {
        columns: function () {
            return isMobile() ? 3 : 1;
        }
    },
    setup: function () {
        const visible = reactive({
            uuid: false,
            udid: false
        });

        const logoutForm = useForm(null);
        const resendVerificationEmailForm = useForm(null);
        const syncUserNameForm = useForm(null);

        return {
            formatTime,
            invertValue,
            redirectToRoute,
            logoutForm,
            resendVerificationEmailForm,
            syncUserNameForm,
            visible
        }
    },
    components: {
        PageLayout,
        NCard,
        NDescriptions,
        NDescriptionsItem,
        NGrid,
        NGridItem,
        NButton,
        NSpace
    }
}
</script>
