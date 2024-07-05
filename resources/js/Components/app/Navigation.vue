<template>
    <nav class="min-w-[200px]">
        <div class="h-[80px] px-3 flex items-center gap-3">
            <Link :href="route('dashboard')">
            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
            </Link>
        </div>
    </nav>
</template>

<script setup>
    // Imports
    import {
        Link
    } from '@inertiajs/vue3';
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';

    // Uses

    // Refs

    // Props & Emits

    // Computed

    // Methods

    // Hooks
</script>

<style scoped>

</style>
