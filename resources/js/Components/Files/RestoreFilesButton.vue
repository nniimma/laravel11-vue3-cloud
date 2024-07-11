<template>
    <primary-button @click="onClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900  border border-gray-200 rounded-lg hover:bg-blue-800 focus:z-10 focus:ring-2 focus:ring-blue-500 ">
        <ArrowLeftOnRectangleIcon   class="h-4 w-4 mr-2" />
        Restore
    </primary-button>

    <confirmation-dialog :show="showConfirmationDialog" message="Are you sure you want to restore the selected files?"
        @cancle="onCancle" @confirm="onConfirm" />
</template>

<script setup>
    // Imports
    import ConfirmationDialog from '@/Components/ConfirmationDialog.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import { showErrorDialog, showErrorNotification, showSuccessNotification } from '@/event-bus';
    import {
        ArrowLeftOnRectangleIcon  
    } from '@heroicons/vue/20/solid';
    import {
        useForm,
        usePage
    } from '@inertiajs/vue3';
    import {
        ref
    } from 'vue';

    // Uses
    const page = usePage()
    const form = useForm({
        all: null,
        ids: [],
        parent_id: null
    })

    // Refs
    const showConfirmationDialog = ref(false)

    // Props & Emits
    const props = defineProps({
        allSelected: {
            type: Boolean,
            required: false,
            default: false
        },
        selectedIds: {
            type: Array,
            required: false
        }
    })

    const emit = defineEmits(['restore'])

    // Computed
    // Methods
    function onClick() {
        if (!props.allSelected && !props.selectedIds.length) {
            // showErrorDialog('Please select at least one file to restore.')
            showErrorNotification('Please select at least one file to restore.')
            return
        }
        showConfirmationDialog.value = true
    }

    function onCancle() {
        showConfirmationDialog.value = false
    }

    function onConfirm() {
        if (props.allSelected) {
            form.all = true
        } else {
            form.ids = props.selectedIds
        }

        form.put(route('files.restore'), {
            onSuccess: () => {
                showConfirmationDialog.value = false
                emit('restore')
                showSuccessNotification('Selected files have been restored.')
            }
        })
    }

    // Hooks
</script>

<style scoped>

</style>
