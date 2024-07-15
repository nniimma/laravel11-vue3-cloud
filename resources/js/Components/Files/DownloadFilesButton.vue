<template>
    <primary-button @click="download" class="">
        <ArrowDownOnSquareStackIcon class="h-5 w-5 mr-2" />

        Download
    </primary-button>
</template>

<script setup>
    // Imports
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import {
        showErrorNotification
    } from '@/event-bus';
    import {
        httpGet
    } from '@/Helper/Http-helper';
    import {
        ArrowDownOnSquareStackIcon
    } from '@heroicons/vue/20/solid';
    import {
        useForm,
        usePage
    } from '@inertiajs/vue3';

    // Uses
    const page = usePage()

    // Refs

    // Props & Emits
    const props = defineProps({
        all: {
            type: Boolean,
            required: false,
            default: false
        },
        ids: {
            type: Array,
            required: false
        },
        sharedWithMe: false,
        sharedByMe: false
    })

    // Computed
    // Methods
    function download() {
        if (!props.all && props.ids.length == 0) {
            showErrorNotification('Please select at least one file to download.')
            return
        }

        const p = new URLSearchParams()
        if (page.props.folder?.id) {
            p.append('parent_id', page.props.folder?.id)
        }
        if (props.all) {
            p.append('all', props.all ? 1 : 0)
        } else {
            for (let id of props.ids) {
                p.append('ids[]', id)
            }
        }

        let url = route('files.download')
        if (props.sharedWithMe) {
            url = route('files.downloadSharedWithMe')
        } else if (props.sharedByMe) {
            url = route('files.downloadSharedByMe')
        }

        httpGet(url + '?' + p.toString()).then(response => {
            if (!response.url) return;

            const a = document.createElement('a')
            a.download = response.fileName
            a.href = response.url
            a.click();
        })
    }

    // Hooks
</script>

<style scoped>

</style>
