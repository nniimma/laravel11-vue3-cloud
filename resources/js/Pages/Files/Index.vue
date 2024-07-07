<template>

    <Head title="Files" />

    <AuthenticatedLayout>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file.owner }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file.updated_at }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ file.size }}</td>
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
        Head, router
    } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

    // Uses
    // Refs
    // Props & Emits
    const {
        files
    } = defineProps({
        files: Object
    })
    // Computed
    // Methods
    function openFolder(file){
        if(!file.is_folder){
            return
        }

        router.visit(route('files.index', {folder: file.path}))
    }
    // Hooks
</script>

<style scoped>

</style>
