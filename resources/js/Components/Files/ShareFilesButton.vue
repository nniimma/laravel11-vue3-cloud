<template>
    <primary-button @click="onClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900  border border-gray-200 rounded-lg hover:bg-blue-800 focus:z-10 focus:ring-2 focus:ring-blue-500 mr-2">
        <ShareIcon   class="h-4 w-4 mr-2" />
        Share
    </primary-button>

    <share-files-modal v-model="showEmailModal" :all-selected="allSelected" :selected-ids="selectedIds"/>
</template>

<script setup>
    // Imports
    import ShareFilesModal from '@/Components/Files/ShareFileButton/ShareFilesModal.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import { showErrorNotification, showSuccessNotification } from '@/event-bus';
    import {
        ShareIcon  
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
    const showEmailModal = ref(false)

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


    // Computed
    // Methods
    function onClick() {
        if (!props.allSelected && !props.selectedIds.length) {
            showErrorNotification('Please select at least one file to share.')
            return
        }
        showEmailModal.value = true
    }

    // Hooks
</script>

<style scoped>

</style>
