<template>
    <Modal :closable="false" :mask-closable="false" v-model="show" title="歌曲上传(网易云专版)">
        <Form :model="music">
            <FormItem prop="link">
                <Input @on-search="parseShareLink" search enter-button="解析" type="text" v-model="link"
                       placeholder="网易云音乐歌曲分享链接">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="id">
                <Input type="text" v-model="music.id" placeholder="网易云音乐ID">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
                <span v-if="result">查询结果: {{ result }}</span>
            </FormItem>
            <FormItem prop="song_id">
                <Input type="text" v-model="music.song_id" placeholder="自定义歌曲ID">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
                <Button @click="getLatestSongID">自动获取歌曲ID</Button>
            </FormItem>
        </Form>

        <template slot="footer">
            <Button type="primary" @click="cancel">取消</Button>
            <Button :loading="request.loading" type="primary" @click="upload">上传</Button>
        </template>
    </Modal>
</template>

<script>
import {request} from "../../../../../js/helper";

export default {
    name: "UploadNetease",
    props: {
        remove: Function,
        callback: Function
    },
    watch: {
        'music.id': function () {
            this.getMusicInfo();
        }
    },
    data: function () {
        return {
            show: true,
            link: null,
            result: null,
            request: {
                loading: false
            },
            music: {
                id: null,
                song_id: null
            }
        }
    },
    methods: {
        cancel: function () {
            this.remove();
        },
        debounce: _.debounce(function () {
            const that = this;
            const musicID = this.music.id;
            request('GET', `/api/tools/song/netease/info/${musicID}`, {
                onSuccess: function (response) {
                    const song = response.data.songs[0];
                    let result = song['name'];
                    result += ' - ';
                    const artists = _.map(song.artists, function (artist) {
                        return artist.name;
                    });

                    result += artists.join(' / ');
                    that.result = result;
                }
            });
        }, 500),
        parseShareLink: function () {
            const link = this.link;
            if (link.indexOf('?') > 0) {
                const query = link.split('?')[1];
                const queries = require('querystring').parse(query);
                this.music.id = queries['id'] || null;
            }
        },
        getMusicInfo: function () {
            this.debounce();
        },
        getLatestSongID: function () {
            const that = this;
            request('GET', '/api/tools/song/latest/id', {
                onSuccess: function (response) {
                    that.music.song_id = response.data.id;
                }
            });
        },
        upload: function () {
            const that = this;
            request('POST', '/api/tools/song/netease/upload', {
                data: that.music,
                request: that.request,
                onSuccess: function (response) {
                    that.remove();

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
