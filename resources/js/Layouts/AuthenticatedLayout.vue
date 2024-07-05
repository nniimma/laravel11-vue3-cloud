<template>
    <div class="h-screen bg-gray-50 flex w-full gap-4">
        <navigation></navigation>

        <main class="flex flex-col flex-1 px-4 overflow-hidden">
            <div class="flex items-center justify-between w-full">
                <search-form></search-form>
                <user-settings-drop-down></user-settings-drop-down>
            </div>
            <div class="flex flex-1 flex-col overflow-hidden">
                <slot/>
            </div>
        </main>
    </div>
</template>

<script setup>
    // Imports
    import navigation from '@/Components/app/Navigation.vue'
    import SearchForm from '@/Components/app/SearchForm.vue'
    import UserSettingsDropDown from '@/Components/app/UserSettingsDropDown.vue'
    // Uses

    // Refs

    // Props & Emits

    // Computed

    // Methods

    // Hooks
</script>


