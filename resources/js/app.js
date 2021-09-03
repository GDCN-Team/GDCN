import jquery from 'jquery';
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import {Ziggy} from './ziggy.js';
import route from 'ziggy';
import {ZiggyVue} from "ziggy/vue";

require("./bootstrap");

jquery(function () {
    window.$ = jquery;
    window.$route = (n, t, r) => route(n, t, r, Ziggy);

    if (window.location.protocol === 'http:') {
        window.location.href = window.location.href.replace('http', 'https');
    }

    window.app = createInertiaApp({
        resolve: name => require(`../views/Pages/${name}`),
        setup: function (config) {
            const app = createApp({
                render: function () {
                    return h(config.App, config.props);
                }
            });

            app.use(config.plugin);
            app.use(ZiggyVue, Ziggy);

            app.mount(config.el);
            return app;
        }
    }).then(function () {
        InertiaProgress.init();
    });
});
