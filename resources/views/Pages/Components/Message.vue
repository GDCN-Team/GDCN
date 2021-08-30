<template></template>

<script>
import {useMessage} from "naive-ui";
import _ from "lodash";
import {watchEffect} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "Message",
    setup: function () {
        window.$message = useMessage();

        watchEffect(function () {
            const $page = usePage();
            const messages = $page.props.value.server.messages;

            if (messages !== null) {
                _.map(messages, function (message) {
                    if (message.type && message.content) {
                        $message[message.type](message.content);
                    }
                });
            }
        });
    }
}
</script>
