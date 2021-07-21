<template>
    <layout>
        <i-header>
            <i-menu active-name="proxy" mode="horizontal" theme="dark">
                <menu-item name="proxy" to="https://dl.geometrydashchinese.com">
                    GDProxy
                </menu-item>
            </i-menu>
        </i-header>
        <i-content class="text-center container">
            <br><br>
            <h1>GDProxy</h1>
            <br><br>
            <Row :gutter="10">
                <Col span="24">
                    <Card class="download" shadow>
                        <template slot="title">
                            <h3>下载</h3>
                        </template>
                        <Button to="https://cdn.geometrydashchinese.com/client/GDProxy.apk">
                            <Icon type="logo-android"></Icon>
                            Android
                        </Button>
                        <Dropdown>
                            <Button>
                                <Icon type="logo-windows"></Icon>
                                Windows
                            </Button>
                            <DropdownMenu slot="list">
                                <DropdownItem>
                                    <a href="https://cdn.geometrydashchinese.com/client/GDProxy.exe">无资源包</a>
                                </DropdownItem>
                                <DropdownItem>
                                    <a href="https://cdn.geometrydashchinese.com/client/GDProxy.zip">有资源包</a>
                                </DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                        <Button disabled to="https://cdn.geometrydashchinese.com/client/GDProxy.ipa">
                            <Icon type="logo-apple"></Icon>
                            IOS
                        </Button>
                    </Card>
                </Col>
            </Row>
            <br><br>
            <Row :gutter="10">
                <Col span="24">
                    <Card class="traffic" dis-hover>
                        <h3>流量公示</h3>
                        <br>
                        <Table :columns="columns" :data="traffics"></Table>
                    </Card>
                </Col>
            </Row>
        </i-content>
        <i-footer class="footer text-center">
            <a href="https://geometrydashchinese.com">几何冲刺玩家网</a>
            <a href="https://gf.geometrydashchinese.com">GDCN</a>
            <a href="https://ng.geometrydashchinese.com">NGProxy</a>
            <br>
            GDCN | 2020 - {{ getCurrentYear() }} &copy; 吉ICP备18006293号
        </i-footer>
    </layout>
</template>

<script>
export default {
    name: "Home",
    data: function () {
        let traffics = [];
        axios.post('api/traffics/1').then(function (response) {
            traffics = response;
        });

        return {
            traffics,
            columns: [
                {
                    title: '流量',
                    key: 'count',
                    align: 'center'
                },
                {
                    title: '日期',
                    key: 'date',
                    align: 'center'
                }
            ]
        }
    },
    methods: {
        getCurrentYear: function () {
            return new Date().getFullYear();
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
    .download, .traffic {
        width: 30%;
        margin: 0 auto;
        text-align: center;
    }
}
</style>
