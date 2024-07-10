<template>
    <Modal :show="show" max-width="md">
        <div class="p-6 text-center bg-red-500">
            <!-- <h2 class="text-2xl mb-2 text-red-600 font-semibold">Error</h2> -->
            <p class="text-xl font-bold text-white">{{ message }}</p>
            <!-- <div class="mt-6 flex justify-end">
                <primary-button @click="close">OK</primary-button>
            </div> -->
        </div>
    </Modal>
</template>

<script setup>
    // Imports
    import Modal from "@/Components/Modal.vue";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import {
        emitter,
        SHOW_ERROR_DIALOGS
    } from "@/event-bus";
    import {
        onMounted,
        ref
    } from "vue";

    // Uses
    // Refs
    const show = ref(false)
    const message = ref('')

    // Props & Emits
    const emit = defineEmits(['close'])

    // Computed
    // Methods
    function close() {
        show.value = false
        message.value = ''
    }

    // Hooks
    onMounted(() => {
        emitter.on(SHOW_ERROR_DIALOGS, ({
            message: msg
        }) => {
            show.value = true
            message.value = msg

            setTimeout(() => {
                close()
            }, 3000)
        })
    })
</script>

<style scoped>

</style>
