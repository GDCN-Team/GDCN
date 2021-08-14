import {computed, isRef, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";

export function redirect(to) {
    return window.location.href = to;
}

export function redirectToRoute(name, params = {}) {
    const to = window.$route(name, params);
    return redirect(to);
}

export function invertValue(object, name, force = null) {
    object[name] = ref(force ?? !object[name]);
}

export function formatTime(time, empty = null) {
    if (!time) {
        return empty;
    }

    return new Date(time).toLocaleString();
}

export function registerMediaListener(condition, callback, callFirst = false) {
    const media = window.matchMedia(`(${condition})`);
    media.addEventListener('change', callback);

    if (callFirst === true) {
        callback(media);
    }
}

export function back() {
    window.history.back();
}

export function isMobile() {
    return window.screen.width > 720;
}
