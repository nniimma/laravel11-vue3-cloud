<template>
    <modal max-width="sm" @show="onShow" :show="props.modelValue">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Share Files</h2>

            <div class="mt-6">
                <input-label for="shareEmail" value="Email:" />

                <text-input ref="emailInput" type="text" id="shareEmail" v-model="form.email" class="mt-1 block w-full"
                    :class="form.errors.email ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
                    placeholder="example@mail.com" @keyup.enter="share" />

                <input-error :message="form.errors.email" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end">
                <secondary-button @click="closeModal">cancel</secondary-button>
                <primary-button class="ml-3" :class="{ 'opacity-25': form.processing }" @click="share"
                    :disable="form.processing">
                    Submit
                </primary-button>
            </div>
        </div>
    </modal>
</template>

<script setup>
    // Imports
    import Modal from "@/Components/Modal.vue";
    import InputLabel from "@/Components/InputLabel.vue";
    import TextInput from "@/Components/TextInput.vue";
    import InputError from "@/Components/InputError.vue";
    import SecondaryButton from "@/Components/SecondaryButton.vue";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import {
        useForm,
        usePage
    } from "@inertiajs/vue3";
    import {
        nextTick,
        ref
    } from "vue";
    import {
        showSuccessNotification
    } from "@/event-bus";

    // Uses
    const form = useForm({
        email: null,
        all: false,
        ids: [],
        parent_id: null
    })

    const page = usePage()

    // Refs
    const emailInput = ref(null)

    // Props & Emits
    const props = defineProps({
        modelValue: Boolean,
        allSelected: Boolean,
        selectedIds: Array
    })

    const emit = defineEmits(['update:modelValue'])

    // Computed
    // Methods
    function onShow() {
        nextTick(() => {
            emailInput.value.focus()
        })
    }

    function share() {
        form.parent_id = page.props.folder.id
        if (props.allSelected) {
            form.all = true;
            form.ids = [];
        } else {
            form.ids = props.selectedIds
        }
        const email = form.email
        form.post(route('files.share'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal()
                form.reset()
                showSuccessNotification(`Selected files have been shared to "${email}" successfully.`)
            },
            onError: () => emailInput.value.focus()
        })
    }

    function closeModal() {
        emit('update:modelValue')
        form.clearErrors()
        form.reset()
    }
    // Hooks
</script>

<style scoped>

</style>
