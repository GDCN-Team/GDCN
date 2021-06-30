<template>
    <a-modal :footer="null" :visible="true" title="关卡搬出" @cancel="back">
        <a-form-model :model="form" @submit="form.post('/tools/level/trans:out')" @submit.native.prevent>
            <a-form-model-item label="服务器">
                <a-select v-model="form.server">
                    <a-select-option value="official">
                        官服
                    </a-select-option>
                </a-select>
            </a-form-model-item>

            <!-- level ID -->
            <a-form-model-item :help="errors.levelID"
                               :validate-status="this.checkValidateStatus(errors.levelID, this.form)"
                               has-feedback>
                <a-input v-model="form.levelID" placeholder="关卡ID" required type="number"></a-input>
            </a-form-model-item>

            <!-- Custom Song -->
            <a-form-model-item label="关卡歌曲状态">
                <a-select v-model="form.songType" @select="switchSongType">
                    <a-select-option value="original">
                        不变
                    </a-select-option>
                    <a-select-option value="audioTrack">
                        改为其他官方歌曲
                    </a-select-option>
                    <a-select-option value="customSong">
                        改为其他NG歌曲
                    </a-select-option>
                </a-select>
            </a-form-model-item>

            <!-- Audio Track -->
            <a-form-model-item v-if="form.songType === 'audioTrack'" label="歌曲选择">
                <a-select v-model="form.songID" placeholder="请选择歌曲">
                    <a-select-option value="-1">Practice: Stay Inside Me - OcularNebula</a-select-option>
                    <a-select-option value="0">Stereo Madness - Foreverbound</a-select-option>
                    <a-select-option value="1">Back on Track - DJVI</a-select-option>
                    <a-select-option value="2">Polargeist - Step</a-select-option>
                    <a-select-option value="3">Dry Out - DJVI</a-select-option>
                    <a-select-option value="4">Base after Base - DJVI</a-select-option>
                    <a-select-option value="5">Cant Let Go - DJVI</a-select-option>
                    <a-select-option value="6">Jumper - Waterflame</a-select-option>
                    <a-select-option value="7">Time Machine - Waterflame</a-select-option>
                    <a-select-option value="8">Cycles - DJVI</a-select-option>
                    <a-select-option value="9">xStep - DJVI</a-select-option>
                    <a-select-option value="10">Clutterfunk - Waterflame</a-select-option>
                    <a-select-option value="11">Theory of Everything - DJ-Nate</a-select-option>
                    <a-select-option value="12">Electroman Adventures - Waterflame</a-select-option>
                    <a-select-option value="13">Clubstep - DJ-Nate</a-select-option>
                    <a-select-option value="14">Electrodynamix - DJ-Nate</a-select-option>
                    <a-select-option value="15">Hexagon Force - Waterflame</a-select-option>
                    <a-select-option value="16">Blast Processing - Waterflame</a-select-option>
                    <a-select-option value="17">Theory of Everything 2 - DJ-Nate</a-select-option>
                    <a-select-option value="18">Geometrical Dominator - Waterflame</a-select-option>
                    <a-select-option value="19">Deadlocked - F-777</a-select-option>
                    <a-select-option value="20">Fingerdash - MDK</a-select-option>
                    <a-select-option value="21">The Seven Seas - F-777</a-select-option>
                    <a-select-option value="22">Viking Arena - F-777</a-select-option>
                    <a-select-option value="23">Airborne Robots - F-777</a-select-option>
                    <a-select-option value="24">The Challenge - RobTop</a-select-option>
                    <a-select-option value="25">Payload Dex - Arson</a-select-option>
                    <a-select-option value="26">Beast Mode - Dex Arson</a-select-option>
                    <a-select-option value="27">Machina - Dex Arson</a-select-option>
                    <a-select-option value="28">Years - Dex Arson</a-select-option>
                    <a-select-option value="29">Frontlines - Dex Arson</a-select-option>
                    <a-select-option value="30">Space Pirates - Waterflame</a-select-option>
                    <a-select-option value="31">Striker - Waterflame</a-select-option>
                    <a-select-option value="32">Embers - Dex Arson</a-select-option>
                    <a-select-option value="33">Round 1 - Dex Arson</a-select-option>
                    <a-select-option value="34">Monster Dance Off - F-777</a-select-option>
                    <a-select-option value="35">Press Start - MDK</a-select-option>
                    <a-select-option value="36">Nock Em - Bossfight</a-select-option>
                    <a-select-option value="37">Power Trip - Boom Kitty</a-select-option>
                </a-select>
            </a-form-model-item>

            <!-- NG Song -->
            <a-form-model-item v-if="form.songType === 'customSong'"
                               :help="errors.songID"
                               :validate-status="this.checkValidateStatus(errors.songID, this.form)"
                               has-feedback>
                <a-input v-model="form.songID" placeholder="歌曲ID" required type="number"></a-input>
            </a-form-model-item>

            <!-- Password -->
            <a-form-model-item :help="errors.password"
                               :validate-status="this.checkValidateStatus(errors.password, this.form)"
                               has-feedback>
                <a-input v-model="form.password" placeholder="密码" required></a-input>
            </a-form-model-item>

            <!-- submit -->
            <a-form-model-item>
                <a-button
                    :disabled="this.form.processing"
                    html-type="submit"
                    type="primary">
                    搬运
                </a-button>
            </a-form-model-item>

        </a-form-model>
    </a-modal>
</template>

<script>
import {back, checkValidateStatus} from "../../../Helpers";
import Layout from '../../Common/Layout';

export default {
    name: "TransOut",
    layout: Layout,
    props: {
        errors: Object
    },
    data() {
        return {
            visible: true,
            form: this.$inertia.form({
                server: 'official',
                songType: 'original',
                songID: null,
                levelID: null
            })
        }
    },
    methods: {
        back, checkValidateStatus,
        switchSongType: function (value) {
            switch (value) {
                case 'audioTrack':
                    this.form.songID = '0';
                    break;
                case 'customSong':
                    this.form.songID = null;
            }
        }
    }
}
</script>

<style scoped>

</style>
