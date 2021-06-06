<template>
    <a-form-model-item>
        <a-checkbox v-model="form[name]">
            <slot></slot>
        </a-checkbox>
    </a-form-model-item>
</template>

<script>
export default {
    name: "CheckBox",
    props: {
        form: Object,
        name: String
    }
}
</script>

<style scoped>

</style>
