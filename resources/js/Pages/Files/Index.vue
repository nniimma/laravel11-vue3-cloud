<template>

    <Head title="Files" />

    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 mb-3">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li v-for="ancestor of ancestors.data" :key="ancestor.id" class="inline-flex items-center">
                    <Link v-if="!ancestor.parent_id" :href="route('files.index')"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 gap-1">
                    <HomeIcon class="w-4 h-4" />
                    My Files
                    </Link>
                    <div v-else class="flex items-center">
                        <ChevronRightIcon class="w-4 h-4" />
                        <Link :href="route('files.index', { folder: ancestor.path })"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                        {{ ancestor . name }}
                        </Link>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b sticky top-0">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Name</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Owner</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Last Modified</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Size</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="file of allFiles.data" :key="file.id"
                        class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100 cursor-default"
                        @dblclick="openFolder(file)">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex gap-2">
                            <file-icon :file="file" />
                            {{ file . name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file . owner }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ file . updated_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file . size }}
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
    import {
        HomeIcon,
        ChevronRightIcon
    } from '@heroicons/vue/20/solid';
    import FileIcon from '@/Components/Files/FileIcon.vue';
    import {
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
    const loadMoreIntersect = ref(null)
    const allFiles = ref({
        data: props.files.data,
        next: props.files.links.next
    })


    // Computed
    // Methods
    function openFolder(file) {
        if (!file.is_folder) {
            return
        }

        router.visit(route('files.index', {
            folder: file.path
        }))
    }

    function loadMore() {
        if (allFiles.value.next === null) {
            return
        }

        httpGet(allFiles.value.next).then(res => {
            allFiles.value.data = [...allFiles.value.data, ...res.data]
            allFiles.value.next = res.links.next
        })
    }

    // Hooks
    onUpdated(() => {
        allFiles.value = {
            data: props.files.data,
            next: props.files.links.next
        }
    })

    onMounted(() => {
        const observer = new IntersectionObserver((entries) => entries.forEach(entry => entry.isIntersecting && loadMore()), {
        rootMargin: '-250px 0px 0px 0px'
    })

    observer.observe(loadMoreIntersect.value)
    })
</script>

<style scoped>

</style>
