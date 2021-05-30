<template>
    <a-form-model-item>
        <a-button :disabled="check()" html-type="submit" type="primary">{{ text }}</a-button>
    </a-form-model-item>
</template>

<script>
export default {
    name: "SubmitBottom",
    props: {
        text: String,
        check: Function
    }
}
</script>

<style scoped>

</style>
