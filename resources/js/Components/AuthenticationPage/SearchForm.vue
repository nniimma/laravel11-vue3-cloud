<template>
    <div class="w-[600px] h-[80px] flex items-center">
        <text-input class="block w-full mr-2" type="text" v-model="search" autocomplete @keyup.enter.prevent="onSearch"
            placeholder="Search for files and folders" />
    </div>
</template>

<script setup>
    // Imports
    import TextInput from '@/Components/TextInput.vue';
    import {
        router,
        useForm
    } from '@inertiajs/vue3';
    import {
        ref
    } from 'vue';
    // Uses

    // Refs
    const search = ref('')

    // Props & Emits

    // Computed

    // Methods
    function onSearch() {
        const params = new URLSearchParams(window.location.search)
        params.set('search', search.value)
        router.get(window.location.pathname + '?' + params.toString())
    }

    // Hooks
</script>

<style scoped>

</style>
