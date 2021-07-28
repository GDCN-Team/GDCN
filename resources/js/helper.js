import Vue from "vue";

export function createComponent(component, props = {}) {
    const remove = function (that = null) {
        if (that === null) {
            that = vm || this;
        }

        document.body.removeChild(that.$el)
        that.$destroy()
    };

    props.remove = remove;
    const vm = new Vue({
        render: h => h(component, {props})
    }).$mount();

    const comp = vm.$children[0];
    comp.remove = remove;

    document.body.appendChild(vm.$el);
    return comp;
}

let maps = [];
export function request(method, url, options) {
    const source = axios.CancelToken.source();

    if (maps[url]) {
        maps[url].cancel();
    }

    if (options.request) {
        options.request.loading = true;
    }

    const request =  axios.request({
        method,
        url,
        data: options.data,
        cancelToken: source.token
    }).then(function (response) {
        if (options.request) {
            options.request.loading = false;
        }

        if (response.data.status === true) {
            if (typeof options.onSuccess === 'function') {
                options.onSuccess(response.data);
            }

            return response.data;
        } else {
            app.$Message.error({
                content: response.data.msg
            });

            if (typeof options.onFailed === 'function') {
                options.onFailed(response.data);
            }
        }
    }).catch(function(error) {
        if (options.request) {
            options.request.loading = false;
        }

        if (error.response.status === 500) {
            app.$Message.error({
                content: '哦不, 服务器发生了一个错误'
            });
        }

        if (typeof options.onError === 'function') {
            options.onError(error.response);
        }
    });

    maps[url] = source;
    return request;
}
