<template>
    <layout>
        <i-header>
            <i-menu active-name="newgrounds" mode="horizontal" theme="dark">
                <menu-item name="newgrounds" to="https://ng.geometrydashchinese.com">
                    NGProxy
                </menu-item>
            </i-menu>
        </i-header>
        <i-content class="text-center container">
            <br><br>
            <h1>NGProxy</h1>
            <br><br>
            <Card class="info">
                <Input :disabled="getting" enter-button placeholder="输入歌曲ID" search type="number"
                       @on-search="getSongInfo"/>
                <br><br>
                {{ songInfo }}
            </Card>
        </i-content>
        <i-footer class="footer text-center">
            <a href="https://geometrydashchinese.com">几何冲刺玩家网</a>
            <a href="https://gf.geometrydashchinese.com">GDCN</a>
            <a href="https://dl.geometrydashchinese.com">GDProxy</a>
            <br>
            GDCN | 2020 - {{ getCurrentYear() }} &copy; 吉ICP备18006293号
        </i-footer>
    </layout>
</template>

<script>
export default {
    name: "Home",
    data: function () {
        return {
            getting: false,
            songInfo: null
        }
    },
    methods: {
        getCurrentYear: function () {
            return new Date().getFullYear();
        },
        getSongInfo: function (songID) {
            let pattern = /^[1-9]\d*$/;
            this.getting = true;
            if (!pattern.test(songID)) {
                this.getting = false;
                this.$Message.error('请输入整数');
            }

            this.songInfo = '获取中...';
        }
    }
}
</script>

<style scoped>
.text-center {
    text-align: center;
}

.footer {
    padding-left: 0;
    padding-right: 0;
}

.container {
    margin-top: 10px;
    margin-bottom: 100px;
}

@media screen and (min-width: 1080px) {
    .info {
        width: 30%;
        margin: 0 auto;
        text-align: center;
    }
}
</style>
