<template>
    <div class="h-screen bg-gray-50 flex w-full gap-4">
        <navigation />

        <main @drop.prevent="handleDrop" @dragover.prevent="onDragOver" @dragleave.prevent="onDragLeave"
            class="flex flex-col flex-1 px-4 overflow-hidden" :class="dragOver ? 'dropzone' : ''">
            <template v-if="dragOver" class="text-gray-500 text-center py-8 text-sm">
                Drop files here to upload
            </template>
            <template v-else>
                <div class="flex items-center justify-between w-full">
                    <search-form />
                    <user-settings-drop-down />
                </div>
                <div class="flex flex-1 flex-col overflow-hidden">
                    <slot />
                </div>
            </template>
        </main>
    </div>

    <error-dialog />
    <notification/>
    <form-progress :form="fileUploadForm" />
</template>

<script setup>
    // Imports
    import navigation from '@/Components/AuthenticationPage/Navigation.vue'
    import SearchForm from '@/Components/AuthenticationPage/SearchForm.vue'
    import UserSettingsDropDown from '@/Components/AuthenticationPage/UserSettingsDropDown.vue'
    import FormProgress from '@/Components/AuthenticationPage/FormProgress.vue'
    import ErrorDialog from '@/Components/ErrorDialog.vue'
    import Notification from '@/Components/Notification.vue'
    import {
        emitter,
        FILE_UPLOAD_STARTED,
        showErrorDialog,
        showSuccessNotification
    } from '@/event-bus'
    import {
        useForm,
        usePage
    } from '@inertiajs/vue3'
    import {
        onMounted,
        ref
    } from 'vue'

    // Uses
    const page = usePage()
    const fileUploadForm = useForm({
        files: [],
        relative_paths: [],
        parent_id: null
    })

    // Refs
    const dragOver = ref(false)

    // Props & Emits

    // Computed

    // Methods
    function onDragOver() {
        dragOver.value = true
    }

    function onDragLeave() {
        dragOver.value = false
    }

    function handleDrop(event) {
        dragOver.value = false
        const files = event.dataTransfer.files
        // console.log(files);
        if (!files.length) {
            return
        }

        uploadFiles(files)
    }

    function uploadFiles(files) {
        fileUploadForm.parent_id = page.props.folder.id
        fileUploadForm.files = files
        fileUploadForm.relative_paths = [...files].map(f => f.webkitRelativePath)

        fileUploadForm.post(route('files.store'), {
            onSuccess: () => {
                showSuccessNotification(`${files.length} files uploaded successfully.`)
            },
            onError: errors => {
                let message = ''
                if (Object.keys(errors).length > 0) {
                    message = errors[Object.keys(errors)[0]]
                } else {
                    message = 'Error during file upload please try again later.'
                }

                showErrorDialog(message)
            },
            onFinish: () => {
                fileUploadForm.clearErrors()
                fileUploadForm.reset()
            }
        })
    }

    // Hooks
    onMounted(() => {
        emitter.on(FILE_UPLOAD_STARTED, uploadFiles)
    })
</script>
<style scoped>
    .dropzone {
        width: 100%;
        height: 100%;
        color: #8d8d8d;
        border: 2px dashed gray;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
