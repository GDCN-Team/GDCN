<template>
    <Layout class="dark:bg-gray-800 h-screen dark:text-white">
        <Header>
            <Menu mode="horizontal" theme="dark" active-name="gdproxy">
                <menu-item to="https://dl.geometrydashchinese.com" name="gdproxy">
                    NGProxy
                </menu-item>
            </Menu>
        </Header>
        <Content class="text-center mt-5 overflow-auto">
            <h1 class="text-2xl">NGProxy</h1>
            <div class="mt-5 w-3/4 lg:w-1/4 mx-auto text-center">
                <Input :disabled="request1.loading" enter-button="查询" placeholder="歌曲ID" search type="number"
                       @on-search="getSongInfo"/>
                <Card shadow v-if="show_song_info_card" class="mt-5 dark:text-black" title="查询结果">
                    <p>歌曲ID: {{ song.id }}</p>
                    <p>歌曲名: {{ song.name }}</p>
                    <p>作曲家ID: {{ song.author_id }}</p>
                    <p>作曲家: {{ song.author_name }}</p>
                    <p>大小: {{ song.size }} MB</p>
                    <p class="mt-5"><a :href="song.download_link">在线试听</a></p>
                    <p v-if="song.video_id"><a :href="`https://www.youtube.com/watch?v=${song.video_id}`">歌曲MV</a></p>
                    <p v-if="song.author_youtube_url"><a :href="song.author_youtube_url">作者Youtube主页</a></p>
                </Card>
            </div>
        </Content>
        <Footer class="dark:bg-gray-800 dark:text-white text-center px-0">
            <p class="text-sm">
                <a href="https://geometrydashchinese.com">几何冲刺玩家网</a>
                <a href="https://gf.geometrydashchinese.com">GDCN</a>
                <a href="https://dl.geometrydashchinese.com">GDProxy</a>
            </p>
            <p class="text-xs w-full">
                Copyright &copy; 2020 - {{ new Date().getFullYear() }} <a href="https://ng.geometrydashchinese.com">NGProxy</a>
                | <a
                href="https://beian.miit.gov.cn">吉ICP备18006293号</a>
            </p>
        </Footer>
    </Layout>
</template>

<script>
import {request} from "../../../../resources/js/helper";

export default {
    name: "Home",
    data: function () {
        return {
            show_song_info_card: false,
            source: null,
            request1: {
                loading: false
            },
            request2: {
                loading: false
            },
            song: {
                author_id: null,
                download_link: null,
                video_id: null,
                author_youtube_url: null
            }
        }
    },
    methods: {
        getSongInfo: function(songID) {
            const that = this;

            that.show_song_info_card = false;
            request('GET', `/api/info/${songID}`, {
                request: that.request1,
                onSuccess: function (response) {
                    that.show_song_info_card = true;
                    that.song = response.data;
                },
                onFailed: function () {
                    that.show_song_info_card = false;
                },
                onError: function () {
                    that.show_song_info_card = false;
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
