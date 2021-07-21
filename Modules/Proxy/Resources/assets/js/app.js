import Vue from 'vue';
import $ from 'jquery';
import axios from 'axios';
import Home from '../../views/Home';
import ViewUI from 'view-design';

window.$ = $;
window.axios = axios;
window.Vue = Vue;

Vue.use(ViewUI);
$(function () {
    window.app = new Vue({
        el: '#app',
        render: h => h(Home)
    });
});
