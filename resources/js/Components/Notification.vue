<template>
    <transition enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div v-if="show" class="fixed bottom-4 left-4 text-white py-2 px-4 rounded-lg shadow-md w-[200px]"
            :class="{
                'bg-emerald-500': type === 'success',
                'bg-red-500': type === 'error'
            }">
            <span>{{ message }}</span>
            <div class="h-1 mt-2 bg-gray-200 rounded-full relative">
                <div :style="{ width: progress + '%', left: (100 - progress) + '%' }"
                     class="h-full bg-gray-500 rounded-full absolute top-0"></div>
            </div>
        </div>
    </transition>
</template>

<script setup>
    // Imports
    import { emitter, SHOW_NOTIFICATION } from "@/event-bus";
    import { onMounted, ref } from "vue";

    // Uses

    // Refs
    const show = ref(false);
    const message = ref('');
    const type = ref('success');
    const progress = ref(100);

    // Props & Emits
    
    // Computed
    // Methods
    function close() {
        show.value = false;
        type.value = '';
        message.value = '';
    }
    
    // Hooks
    onMounted(() => {
        emitter.on(SHOW_NOTIFICATION, ({ type: t, message: msg }) => {
            show.value = true;
            type.value = t;
            message.value = msg;
            progress.value = 100;

            const duration = 5000; // milliseconds
            const interval = 100; // update interval for progress bar

            const timer = setInterval(() => {
                progress.value -= (interval / duration) * 100;
                if (progress.value <= 0) {
                    clearInterval(timer);
                    close();
                }
            }, interval);
        });
    });
</script>

<style scoped>
    /* Add any additional styling for your progress bar here */
</style>
