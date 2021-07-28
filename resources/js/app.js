require("./bootstrap");

import Vue from "vue";
import $ from "jquery";
import ViewUI from 'view-design';

window.$ = $;
window.Vue = Vue;

$(function () {
    Vue.use(ViewUI);
    const el = document.getElementById('app');
    const component = require(`../views/Pages/${el.dataset.component}`).default;

    window.app = new Vue({
        render: h => h(component)
    }).$mount(el);
});
