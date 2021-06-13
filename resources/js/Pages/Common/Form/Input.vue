<template>
    <a-form-model-item :has-feedback="!!errors[name]"
                       :help="errors[name]"
                       :validate-status="errors[name] ? 'error' : null">
        <a-input v-model="form[name]" :placeholder="placeholder" :type="type">
            <a-icon v-if="icon" slot="prefix" :type="icon" style="color:rgba(0,0,0,.25)"></a-icon>
        </a-input>

        <slot class="float-left" name="extra"></slot>
    </a-form-model-item>
</template>

<script>

export default {
    name: "Input",
    props: {
        form: Object,
        name: String,
        placeholder: String,
        icon: String,
        errors: Object,
        type: {
            type: String,
            default: 'text'
        }
    }
}
</script>

<style scoped>

</style>
