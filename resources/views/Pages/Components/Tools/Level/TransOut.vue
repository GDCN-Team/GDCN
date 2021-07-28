<template>
    <Modal :closable="false" :mask-closable="false" v-model="show" title="关卡搬出">
        <Form :model="level">
            <FormItem prop="server">
                <Select placeholder="选择账号" v-model="level.link">
                    <Option v-for="(link,key) in links" :value="link.id" :key="key">{{ link.target_name }}[{{ link.target_account_id }}, {{ link.target_user_id }}]</Option>
                </Select>
            </FormItem>
            <FormItem v-if="level.link" prop="password">
                <Input type="password" v-model="level.password" placeholder="密码">
                    <Icon type="ios-lock-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="id">
                <Input type="text" v-model="level.id" placeholder="关卡ID">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="song_type">
                <Select placeholder="歌曲处理方式" v-model="level.song_type">
                    <Option value="original">保持不变</Option>
                    <Option value="audio_track">更变为官方歌曲</Option>
                    <Option value="newgrounds">更变为NG歌曲</Option>
                </Select>
            </FormItem>
            <FormItem v-if="level.song_type !== 'original' && level.song_type !== null" prop="song">
                <Select v-if="level.song_type === 'audio_track'" placeholder="选择歌曲" v-model="level.song">
                    <Option value="-1">Practice: Stay Inside Me - OcularNebula</Option>
                    <Option value="0">Stereo Madness - Foreverbound</Option>
                    <Option value="1">Back on Track - DJVI</Option>
                    <Option value="2">Polargeist - Step</Option>
                    <Option value="3">Dry Out - DJVI</Option>
                    <Option value="4">Base after Base - DJVI</Option>
                    <Option value="5">Cant Let Go - DJVI</Option>
                    <Option value="6">Jumper - Waterflame</Option>
                    <Option value="7">Time Machine - Waterflame</Option>
                    <Option value="8">Cycles - DJVI</Option>
                    <Option value="9">xStep - DJVI</Option>
                    <Option value="10">Clutterfunk - Waterflame</Option>
                    <Option value="11">Theory of Everything - DJ-Nate</Option>
                    <Option value="12">Electroman Adventures - Waterflame</Option>
                    <Option value="13">Clubstep - DJ-Nate</Option>
                    <Option value="14">Electrodynamix - DJ-Nate</Option>
                    <Option value="15">Hexagon Force - Waterflame</Option>
                    <Option value="16">Blast Processing - Waterflame</Option>
                    <Option value="17">Theory of Everything 2 - DJ-Nate</Option>
                    <Option value="18">Geometrical Dominator - Waterflame</Option>
                    <Option value="19">Deadlocked - F-777</Option>
                    <Option value="20">Fingerdash - MDK</Option>
                    <Option value="21">The Seven Seas - F-777</Option>
                    <Option value="22">Viking Arena - F-777</Option>
                    <Option value="23">Airborne Robots - F-777</Option>
                    <Option value="24">The Challenge - RobTop</Option>
                    <Option value="25">Payload Dex - Arson</Option>
                    <Option value="26">Beast Mode - Dex Arson</Option>
                    <Option value="27">Machina - Dex Arson</Option>
                    <Option value="28">Years - Dex Arson</Option>
                    <Option value="29">Frontlines - Dex Arson</Option>
                    <Option value="30">Space Pirates - Waterflame</Option>
                    <Option value="31">Striker - Waterflame</Option>
                    <Option value="32">Embers - Dex Arson</Option>
                    <Option value="33">Round 1 - Dex Arson</Option>
                    <Option value="34">Monster Dance Off - F-777</Option>
                    <Option value="35">Press Start - MDK</Option>
                    <Option value="36">Nock Em - Bossfight</Option>
                    <Option value="37">Power Trip - Boom Kitty</Option>
                </Select>

                <Input v-else type="text" v-model="level.song" placeholder="歌曲ID">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
        </Form>

        <template slot="footer">
            <Button type="primary" @click="cancel">取消</Button>
            <Button :loading="request.loading" type="primary" @click="transOut">搬出</Button>
        </template>
    </Modal>
</template>

<script>
import {request} from "../../../../../js/helper";

export default {
    name: "TransOut",
    props: {
        remove: Function,
        callback: Function
    },
    mounted: function() {
        this.getLinkedAccounts();
    },
    data: function () {
        return {
            show: true,
            request: {
                loading: false
            },
            links: [],
            level: {
                link: null,
                id: null,
                song_type: null,
                song: null,
                password: null
            }
        }
    },
    methods: {
        cancel: function() {
            this.remove()
        },
        getLinkedAccounts: function () {
            const that = this;
            request('GET', '/api/tools/account/link/list', {
                onSuccess: function (response) {
                    that.links = response.data;
                }
            })
        },
        transOut: function() {
            const that = this;
            request('POST', '/api/tools/level/trans/out', {
                data: that.level,
                request: that.request,
                onSuccess: function (response) {
                    that.remove();

                    app.$Message.success({
                        content: response.msg
                    });

                    if (typeof that.callback === 'function') {
                        that.callback(response);
                    }
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
