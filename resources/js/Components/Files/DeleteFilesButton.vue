<template>
    <danger-button @click="onDeleteClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900  border border-gray-200 rounded-lg hover:bg-red-700 hover:bg-gray-900 focus:z-10 focus:ring-2 focus:ring-black focus:text-black">
        <TrashIcon class="h-4 w-4 mr-2" />
        Delete
    </danger-button>

    <confirmation-dialog :show="showDeleteDialog" message="Are you sure you want to delete the selected files?"
        @cancle="onDeleteCancle" @confirm="onDeleteConfirm" />
</template>

<script setup>
    // Imports
    import ConfirmationDialog from '@/Components/ConfirmationDialog.vue'
    import DangerButton from '@/Components/DangerButton.vue'
    import { showErrorDialog } from '@/event-bus';
    import {
        TrashIcon
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
    const deleteFilesForm = useForm({
        all: null,
        ids: [],
        parent_id: null
    })

    // Refs
    const showDeleteDialog = ref(false)

    // Props & Emits
    const props = defineProps({
        deleteAll: {
            type: Boolean,
            required: false,
            default: false
        },
        deleteIds: {
            type: Array,
            required: false
        }
    })

    const emit = defineEmits(['delete'])

    // Computed
    // Methods
    function onDeleteClick() {
        if (!props.deleteAll && !props.deleteIds.length) {
            showErrorDialog('Please select at least one file to delete.')
            return
        }
        showDeleteDialog.value = true
    }

    function onDeleteCancle() {
        showDeleteDialog.value = false
    }

    function onDeleteConfirm() {
        deleteFilesForm.parent_id = page.props.folder.id
        if (props.deleteAll) {
            deleteFilesForm.all = true
        } else {
            deleteFilesForm.ids = props.deleteIds
        }

        deleteFilesForm.delete(route('files.destroy'), {
            onSuccess: () => {
                showDeleteDialog.value = false
                emit('delete')
            }
        })
    }

    // Hooks
</script>

<style scoped>

</style>
