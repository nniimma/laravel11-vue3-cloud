<template>

    <Head title="Files" />

    <AuthenticatedLayout>
        <nav class="flex items-center justify-between p-1 mb-3">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li v-for="ancestor of ancestors.data" :key="ancestor.id" class="inline-flex items-center">
                    <Link v-if="!ancestor.parent_id" :href="route('files.index')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 gap-1">
                        <HomeIcon class="w-4 h-4"/>
                        My Files
                    </Link>
                    <div v-else class="flex items-center">
                        <ChevronRightIcon   class="w-4 h-4"/>
                        <Link :href="route('files.index', {folder: ancestor.path})" 
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                        {{ ancestor . name }}
                        </Link>
                    </div>
                </li>
            </ol>
        </nav>
        <table class="min-w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Name</th>
                    <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Owner</th>
                    <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Last Modified</th>
                    <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Size</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="file of files.data" :key="file.id"
                    class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100 cursor-default"
                    @dblclick="openFolder(file)">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file . name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file . owner }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file . updated_at }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file . size }}</td>
                </tr>
            </tbody>
        </table>

        <div v-if="!files.data.length" class="py-8 text-center text-md text-gray-400">
            There is no data in this folder.
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

    // Uses
    // Refs
    // Props & Emits
    const {
        files
    } = defineProps({
        files: Object,
        folder: Object,
        ancestors: Object
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
    // Hooks
</script>

<style scoped>

</style>
