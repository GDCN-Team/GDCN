import Vue from 'vue';
import $ from 'jquery';
import Home from '../../views/home';
import Axios from 'axios';
import ViewUI from 'view-design';

window.Vue = Vue;
window.axios = Axios;
window.$ = $;

$(function () {
    Vue.use(ViewUI);
    window.app = new Vue({
        render: h => h(Home)
    }).$mount('#app');
});
