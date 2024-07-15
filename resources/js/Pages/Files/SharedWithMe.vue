<template>

    <Head title="Shared With Me" />

    <AuthenticatedLayout>
        <nav class="flex items-center justify-end p-1 mb-3">
            <div class="flex">
                <download-files-button class="mr-2" :all="allSelected" :ids="selectedIds" :shared-with-me="true"/>
            </div>
        </nav>
        <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b sticky top-0">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-[30px] max-w-[30px] pr-0">
                            <checkbox @change="onSelectAllChange" v-model:checked="allSelected" />
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Name</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Path</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="file of allFiles.data" :key="file.id" @click="$event => toggleFileSelect(file)"
                        class=" border-b transition duration-300 ease-in-out hover:bg-blue-200 cursor-default"
                        :class="(selected[file.id] || allSelected) ? 'bg-blue-50' : 'bg-white'">
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-[30px] max-w-[30px] pr-0">
                            <checkbox @change="event => onSelectedCheckBoxChange(file)" v-model="selected[file.id]" :checked="selected[file.id] || allSelected" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex gap-2">
                            <file-icon :file="file" />
                            {{ file . name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file . path }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="!allFiles.data.length" class="py-8 text-center text-md text-gray-400">
                There is no data in this folder.
            </div>
            <div ref="loadMoreIntersect">&#12644;</div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
    // Imports
    import {
        Head,
        Link,
        router
    } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import FileIcon from '@/Components/Files/FileIcon.vue';
    import Checkbox from '@/Components/Checkbox.vue';
    import DownloadFilesButton from '@/Components/Files/DownloadFilesButton.vue';
    import {
        computed,
        onMounted,
        onUpdated,
        ref
    } from 'vue';
    import {
        httpGet
    } from '@/Helper/Http-helper';

    // Uses

    // Props & Emits
    const props = defineProps({
        files: Object,
        folder: Object,
        ancestors: Object
    })

    // Refs
    const allSelected = ref(false)
    const selected = ref({})
    const loadMoreIntersect = ref(null)
    const allFiles = ref({
        data: props.files.data,
        next: props.files.links.next
    })


    // Computed
    const selectedIds = computed(() => Object.entries(selected.value).filter(a => a[1]).map(a => a[0]))

    // Methods
    function loadMore() {
        if (allFiles.value.next === null) {
            return
        }

        httpGet(allFiles.value.next).then(res => {
            allFiles.value.data = [...allFiles.value.data, ...res.data]
            allFiles.value.next = res.links.next
        })
    }

    function onSelectAllChange() {
        allFiles.value.data.forEach(f => {
            selected.value[f.id] = allSelected.value
        })
    }

    function toggleFileSelect(file){
        selected.value[file.id] = !selected.value[file.id]
        onSelectedCheckBoxChange(file)
    }

    function onSelectedCheckBoxChange(file){
        if(!selected.value[file.id]){
            allSelected.value = false
        } else{
            let checked = true
            for(let file of allFiles.value.data){
                if(!selected.value[file.id]){
                    checked = false
                    break
                }
            }

            allSelected.value = checked
        }
    }

    function resetForm(){
        allSelected.value = false
        selected.value = {}
    }

    // Hooks
    onUpdated(() => {
        allFiles.value = {
            data: props.files.data,
            next: props.files.links.next
        }
    })

    onMounted(() => {
        const observer = new IntersectionObserver((entries) => entries.forEach(entry => entry.isIntersecting &&
            loadMore()), {
            rootMargin: '-250px 0px 0px 0px'
        })

        observer.observe(loadMoreIntersect.value)
    })
</script>

<style scoped>

</style>
