<template>
    <form class="w-[600px] h-[80px] flex items-center">
        <text-input 
            class="block w-full mr-2" 
            type="text" 
            v-model="form.search" 
            autocomplete 
            placeholder="Search for files and folders"/>
    </form>
</template>

<script setup>
    // Imports
    import TextInput from '@/Components/TextInput.vue';
    import { useForm } from '@inertiajs/vue3';
    // Uses
    const form = useForm({
        search: ''
    })
    // Refs

    // Props & Emits

    // Computed

    // Methods

    // Hooks

    
</script>

<style scoped>
    
</style>