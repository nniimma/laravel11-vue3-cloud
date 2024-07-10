<template>
    <modal :show="show" max-width="md">
        <div class="p-6">
            <h2 class="text-2xl mb-2 font-semibold">Please Confirm</h2>
            <p>{{ message }}</p>

            <div class="mt-6 flex justify-end">
                <secondary-button @click="$event => emit('cancle')">Cancle</secondary-button>
                <danger-button class="ml-3" @click="$event => emit('confirm')">Delete</danger-button>
            </div>
        </div>
    </modal>
</template>

<script setup>
    // Imports
    import Modal from "@/Components/Modal.vue";
    import SecondaryButton from "@/Components/SecondaryButton.vue";
    import DangerButton from "@/Components/DangerButton.vue";

    // Uses
    // Refs
    // Props & Emits
    const props = defineProps({
        show: Boolean,
        message: String
    })
    const emit = defineEmits(['cancle', 'confirm'])

    // Computed
    // Methods
    // Hooks
</script>

<style scoped>

</style>
