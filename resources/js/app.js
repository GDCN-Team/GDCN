require("./bootstrap");

import $ from "jquery";
import Vue from "vue";
import Antd from 'ant-design-vue';
import Inertia from '@inertiajs/inertia';
import {App, plugin} from '@inertiajs/inertia-vue'
import {InertiaProgress} from '@inertiajs/progress'

Vue.use(plugin);
Vue.use(Antd);
InertiaProgress.init();

window.$ = $;
window.Vue = Vue;
window.Inertia = Inertia.Inertia;

$(function () {
    const el = document.getElementById('app');

    window.app = new Vue({
        render: h => h(App, {
            props: {
                initialPage: JSON.parse(el.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default,
            }
        })
    });

    app.$mount(el);
});
