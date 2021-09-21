<template>
    <layout>
        <div :class="container_class">
            <div class="py-5 lg:px-0 px-2.5">
                <n-page-header :title="title" @back="back"/>
                <div class="my-5">
                    <slot></slot>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "./Layout";
import {back} from "../../../js/helper";
import {NPageHeader} from "naive-ui";

export default {
    name: "PageLayout",
    props: {
        class: String,
        title: String
    },
    computed: {
        container_class: function () {
            return `container mx-auto ${this.class}`;
        }
    },
    setup: function () {
        return {back}
    },
    components: {
        Layout,
        NPageHeader
    }
}
</script>
