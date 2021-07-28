<template>
    <Modal :closable="false" :mask-closable="false" v-model="show" title="歌曲上传(外链版)">
        <Form :model="music">
            <FormItem prop="url">
                <Input type="text" v-model="music.url" placeholder="歌曲外链">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="song_id">
                <Input type="text" v-model="music.song_id" placeholder="自定义歌曲ID">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
                <Button @click="getLatestSongID">自动获取歌曲ID</Button>
            </FormItem>
            <FormItem prop="name">
                <Input type="text" v-model="music.name" placeholder="歌曲名">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="author_name">
                <Input type="text" v-model="music.author_name" placeholder="歌手名">
                    <Icon type="ios-star-outline" slot="prepend"></Icon>
                </Input>
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
    name: "UploadLink",
    props: {
        remove: Function,
        callback: Function
    },
    data: function () {
        return {
            show: true,
            music: {
                url: null,
                song_id: null,
                name: null,
                author_name: null
            },
            request: {
                loading: false
            }
        }
    },
    methods: {
        cancel: function() {
            this.remove();
        },
        getLatestSongID: function () {
            const that = this;
            request('GET', '/api/tools/song/latest/id', {
                onSuccess: function (response) {
                    that.music.song_id = response.data.id;
                }
            });
        },
        upload: function() {
            const that = this;
            request('POST', '/api/tools/song/link/upload', {
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
