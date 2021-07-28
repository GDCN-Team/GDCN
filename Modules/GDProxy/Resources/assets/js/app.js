import Vue from 'vue';
import $ from 'jquery';
import Home from '../../views/Home';
import Axios from 'axios';
import ViewUI from 'view-design';

window.Vue = Vue;
window.Axios = Axios;
window.$ = $;

$(function () {
    Vue.use(ViewUI);
    window.app = new Vue({
        render: h => h(Home)
    }).$mount('#app');
});
