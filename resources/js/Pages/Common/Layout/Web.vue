<template>
    <a-layout class="h-screen">
        <Sider></Sider>
        <a-layout>
            <a-layout-content class="bg-gray-800 overflow-auto text-white p-3">
                <slot @back="back"></slot>
            </a-layout-content>
            <Footer></Footer>
        </a-layout>
    </a-layout>
</template>

<script>
import Sider from './Sider';
import Footer from './Footer';

export default {
    components: {
        Sider,
        Footer
    },
    watch: {
        '$page.props.notices': function () {
            this.loadNotices();
        }
    },
    mounted: function () {
        this.loadNotices()
    },
    methods: {
        back: function () {
            history.back();
        },
        loadNotices: function () {
            const notices = this.$page.props.notices;
            for (let notice in notices) {
                if (!notices.hasOwnProperty(notice)) {
                    continue;
                }

                this.$notification[notices[notice].type]({
                    message: notices[notice].message,
                    description: notices[notice].description || ''
                });
            }
        }
    },
    name: "Web"
}

</script>

<style scoped>

</style>
