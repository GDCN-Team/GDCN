<template>
    <page-layout title="关卡搬出">
        <n-card>
            <n-form :model="form">
                <n-form-item
                    :feedback="form.errors.server ?? null"
                    :validation-status="form.errors.server ? 'error' : null"
                    label="服务器"
                    required>
                    <n-select v-model:value="form.server" :options="serverOptions" placeholder="服务器"
                              @update:value="reloadLinkAccounts"></n-select>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.levelID ?? null"
                    :validation-status="form.errors.levelID ? 'error' : null"
                    label="关卡ID"
                    required>
                    <n-input v-model:value="form.levelID" placeholder="关卡ID" type="number"></n-input>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.linkID ?? null"
                    :validation-status="form.errors.linkID ? 'error' : null"
                    label="账号选择"
                    required>
                    <n-select v-model:value="form.linkID" :options="linkAccountOptions" placeholder="账号选择"></n-select>
                </n-form-item>

                <n-form-item
                    :feedback="form.errors.password ?? null"
                    :validation-status="form.errors.password ? 'error' : null"
                    label="密码"
                    required>
                    <n-input v-model:value="form.password" placeholder="密码" type="password"></n-input>
                </n-form-item>

                <n-collapse>
                    <n-collapse-item name="setting" title="关卡设置">
                        <n-form-item
                            :feedback="form.errors.level_name ?? null"
                            :validation-status="form.errors.level_name ? 'error' : null"
                            label="关卡名">
                            <n-input v-model:value="form.level_name" placeholder="关卡名(留空不变)"></n-input>
                        </n-form-item>
                        <n-form-item
                            :feedback="form.errors.level_desc ?? null"
                            :validation-status="form.errors.level_desc ? 'error' : null"
                            label="关卡简介">
                            <n-input v-model:value="form.level_desc" placeholder="关卡简介(留空不变)"></n-input>
                        </n-form-item>
                        <n-form-item
                            :feedback="form.errors.level_desc ?? null"
                            :validation-status="form.errors.level_desc ? 'error' : null"
                            label="关卡歌曲类型">
                            <n-select v-model:value="form.level_song_type" :options="levelSongTypes"
                                      placeholder="关卡歌曲类型"></n-select>
                        </n-form-item>
                        <n-form-item
                            v-if="form.level_song_type !== 'original'"
                            :feedback="form.errors.level_song_id ?? null"
                            :validation-status="form.errors.level_song_id ? 'error' : null"
                            label="关卡歌曲">
                            <n-select v-if="form.level_song_type === 'audio_track'" v-model:value="form.level_song_id"
                                      :options="levelAudioTracks"
                                      placeholder="关卡歌曲"></n-select>
                            <n-input v-else v-model:value="form.level_song_id" placeholder="关卡歌曲ID"
                                     type="number"></n-input>
                        </n-form-item>
                        <n-form-item
                            :feedback="form.errors.level_password ?? null"
                            :validation-status="form.errors.level_password ? 'error' : null"
                            label="关卡密码">
                            <n-input v-model:value="form.level_password" placeholder="关卡密码(留空不变)"
                                     type="number"></n-input>
                            <p class="ml-2"><span>0=NoCopy</span><br><span>1=FreeCopy</span></p>
                        </n-form-item>
                        <n-form-item
                            :feedback="form.errors.level_unlisted ?? null"
                            :validation-status="form.errors.level_unlisted ? 'error' : null"
                            label="关卡Unlisted状态">
                            <n-switch v-model:value="form.level_unlisted"></n-switch>
                        </n-form-item>
                    </n-collapse-item>
                </n-collapse>

                <n-form-item>
                    <n-button
                        :disabled="form.processing"
                        :loading="form.processing"
                        @click="form.post(route('tools.level.trans.out.api'))">
                        搬运
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </page-layout>
</template>

<script>
import {NButton, NCard, NCollapse, NCollapseItem, NForm, NFormItem, NInput, NSelect, NSwitch} from "naive-ui";
import PageLayout from "../../Components/PageLayout";
import {useForm} from "@inertiajs/inertia-vue3";
import {computed} from "vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "TransOut",
    props: {
        servers: Object,
        links: Array
    },
    setup: function (props) {
        const serverOptions = _.map(props.servers, function (server, name) {
            return {
                label: server.alias,
                value: name
            };
        });

        const levelSongTypes = [
            {
                label: '不变',
                value: 'original'
            },
            {
                label: '换成官方歌曲',
                value: 'audio_track'
            },
            {
                label: '换成NG歌曲',
                value: 'newgrounds'
            }
        ];

        const levelAudioTracks = [
            {
                label: "Practice: Stay Inside Me - OcularNebula",
                value: -1
            },
            {
                label: "Stereo Madness - Foreverbound",
                value: 0
            },
            {
                label: "Back on Track - DJVI",
                value: 1
            },
            {
                label: "Polargeist - Step",
                value: 2
            },
            {
                label: "Dry Out - DJVI",
                value: 3
            },
            {
                label: "Base after Base - DJVI",
                value: 4
            },
            {
                label: "Cant Let Go - DJVI",
                value: 5
            },
            {
                label: "Jumper - Waterflame",
                value: 6
            },
            {
                label: "Time Machine - Waterflame",
                value: 7
            },
            {
                label: "Cycles - DJVI",
                value: 8
            },
            {
                label: "xStep - DJVI",
                value: 9
            },
            {
                label: "Clutterfunk - Waterflame",
                value: 10
            },
            {
                label: "Theory of Everything - DJ-Nate",
                value: 11
            },
            {
                label: "Electroman Adventures - Waterflame",
                value: 12
            },
            {
                label: "Clubstep - DJ-Nate",
                value: 13
            },
            {
                label: "Electrodynamix - DJ-Nate",
                value: 14
            },
            {
                label: "Hexagon Force - Waterflame",
                value: 15
            },
            {
                label: "Blast Processing - Waterflame",
                value: 16
            },
            {
                label: "Theory of Everything 2 - DJ-Nate",
                value: 17
            },
            {
                label: "Geometrical Dominator - Waterflame",
                value: 18
            },
            {
                label: "Deadlocked - F-777",
                value: 19
            },
            {
                label: "Fingerdash - MDK",
                value: 20
            },
            {
                label: "The Seven Seas - F-777",
                value: 21
            },
            {
                label: "Viking Arena - F-777",
                value: 22
            },
            {
                label: "Airborne Robots - F-777",
                value: 23
            },
            {
                label: "The Challenge - RobTop",
                value: 24
            },
            {
                label: "Payload Dex - Arson",
                value: 25
            },
            {
                label: "Beast Mode - Dex Arson",
                value: 26
            },
            {
                label: "Machina - Dex Arson",
                value: 27
            },
            {
                label: "Years - Dex Arson",
                value: 28
            },
            {
                label: "Frontlines - Dex Arson",
                value: 29
            },
            {
                label: "Space Pirates - Waterflame",
                value: 30
            },
            {
                label: "Striker - Waterflame",
                value: 31
            },
            {
                label: "Embers - Dex Arson",
                value: 32
            },
            {
                label: "Round 1 - Dex Arson",
                value: 33
            },
            {
                label: "Monster Dance Off - F-777",
                value: 34
            },
            {
                label: "Press Start - MDK",
                value: 35
            },
            {
                label: "Nock Em - Bossfight",
                value: 36
            },
            {
                label: "Power Trip - Boom Kitty",
                value: 37
            }
        ];

        let linkAccountOptions;
        const server = computed(function () {
            const querystring = require('querystring');
            return querystring.parse(window.location.search)['?server'];
        });

        const reloadLinkAccounts = function (server) {
            Inertia.reload({
                data: {server},
                only: ['links'],
                onSuccess: function () {
                    getLinkAccountOptions(props.links);
                }
            })
        }

        const getLinkAccountOptions = function (links) {
            linkAccountOptions = _.map(links, function (link) {
                return {
                    label: link.target_name,
                    value: link.id
                };
            });
        }

        getLinkAccountOptions(props.links);
        const form = useForm({
            server: server.value ?? 'GDProxy',
            levelID: null,
            linkID: null,
            password: null,
            level_name: null,
            level_desc: null,
            level_song_type: 'original',
            level_song_id: null,
            level_unlisted: null,
            level_password: null
        });

        return {linkAccountOptions, reloadLinkAccounts, serverOptions, levelSongTypes, levelAudioTracks, form}
    },
    components: {
        PageLayout,
        NCard,
        NForm,
        NFormItem,
        NSelect,
        NInput,
        NButton,
        NSwitch,
        NCollapse,
        NCollapseItem
    }
}
</script>
