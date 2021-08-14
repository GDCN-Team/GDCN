<template></template>

<script>
import {useDialog} from "naive-ui";

export default {
    name: "Dialog",
    setup: function () {
        window.$dialog = useDialog();
    }
}
</script>
