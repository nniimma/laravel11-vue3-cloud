<template>
    <primary-button @click="onClick"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900  border border-gray-200 rounded-lg hover:bg-blue-800 focus:z-10 focus:ring-2 focus:ring-blue-500 mr-2">
        <StarSolidIcon class="h-4 w-4 mr-2" />
        Add to Favorites
    </primary-button>
</template>

<script setup>
    // Imports
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import {
        showErrorNotification,
        showSuccessNotification
    } from '@/event-bus';
    import {
        StarIcon as StarSolidIcon
    } from '@heroicons/vue/20/solid'
    import {
        useForm,
        usePage
    } from '@inertiajs/vue3';

    // Uses
    const page = usePage()
    const form = useForm({
        all: null,
        ids: [],
        parent_id: null
    })

    // Refs

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
            showErrorNotification('Please select at least one file to add to favorites.')
            return
        }

        form.parent_id = page.props.folder.id
        if (props.allSelected) {
            form.all = true
        } else {
            form.ids = props.selectedIds
        }

        form.post(route('files.favorites'), {
            onSuccess: () => {
                form.ids = []
                showSuccessNotification('Selected files have been added to favorites.')
            }
        })
    }

    // Hooks
</script>

<style scoped>

</style>
