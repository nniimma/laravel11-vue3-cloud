<template>
    <modal :show="modelValue">
        Show modal
    </modal>
</template>

<script setup>
    // Imports
    import Modal from "@/Components/Modal.vue";
    // Uses
    // Refs
    // Props & Emits
    const{modelValue} = defineProps({
        modelValue: Boolean
    })
    // Computed
    // Methods
    // Hooks
    
</script>

<style scoped>
    
</style>