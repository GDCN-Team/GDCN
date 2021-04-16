require("./bootstrap");

import $ from "jquery";
import Vue from "vue";
import Antd from 'ant-design-vue';

Vue.use(Antd);
window.$http = window.axios;
window.$request = async function (request, callback = null) {
    if (!request.url) {
        return null;
    }

    if (!request.loading) {
        request.loading = true;
    }

    axios.post(request.url, request.data || {}).then(function (req) {
        if (req.data.status === false) {
            app.$message.error(req.data.msg || request.default_failed_text || '失败');
        } else {
            if (request.redirect) {
                window.location.href = request.redirect;
            }

            app.$message.success(req.data.msg || request.default_success_text || '成功!');
        }

        if (request.loading) {
            request.loading = false;
        }

        if (callback) {
            callback(req.data);
        }
    }).catch(function (req) {
        app.$message.error('请求错误, 错误码: ' + req.response.status);

        if (req.response.status === 422) {
            for (let error in req.response.data.errors) {
                if (req.response.data.errors.hasOwnProperty(error)) {
                    app.$message.error(req.response.data.errors[error]);
                }
            }
        }

        if (request.loading) {
            request.loading = false;
        }

        if (request.onerror) {
            request.onerror(req);
        }

        throw req;
    });
};

window.$ = $;
window.Vue = Vue;

$(function () {
    window.app = new Vue({
        methods: {
            redirect: url => {
                window.location.href = url;
            }
        }
    });

    app.$mount("#app");
});
