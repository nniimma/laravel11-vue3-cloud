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

            <div class="flex">
                <label class="mr-2 border-2 border-gray-400 rounded-md p-2">
                    Only Favorites
                    <checkbox @change="showOnlyFavorites" v-model:checked="onlyFavorites" />
                </label>
                <download-files-button class="mr-2" :all="allSelected" :ids="selectedIds" />
                <delete-files-button :delete-all="allSelected" :delete-ids="selectedIds" @delete="onDelete" />
            </div>
        </nav>
        <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b sticky top-0">
                    <tr>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-[30px] max-w-[30px] pr-0">
                            <checkbox @change="onSelectAllChange" v-model:checked="allSelected" />
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-[30px] max-w-[30px]">
                        </th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Name</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Owner</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Last Modified</th>
                        <th class="text-sm font-medium text-gray-900 px-6 py-4 text-left">Size</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="file of allFiles.data" :key="file.id" @dblclick="openFolder(file)"
                        @click="$event => toggleFileSelect(file)"
                        class=" border-b transition duration-300 ease-in-out hover:bg-blue-200 cursor-default"
                        :class="(selected[file.id] || allSelected) ? 'bg-blue-50' : 'bg-white'">
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-[30px] max-w-[30px] pr-0">
                            <checkbox @change="event => onSelectedCheckBoxChange(file)" v-model="selected[file.id]"
                                :checked="selected[file.id] || allSelected" />
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium gap-2 w-[30px] max-w-[30px] text-yellow-500">
                            <div @click.stop.prevent="addRemoveFavorite(file)">
                                <StarSolidIcon v-if="file.is_favourite" class="w-5 h-5" />
                                <StarOutlineIcon v-else class="w-5 h-5" />
                            </div>
                        </td>
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
        router,
        useForm,
        usePage
    } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import {
        StarIcon as StarSolidIcon,
        ChevronRightIcon,
        HomeIcon
    } from '@heroicons/vue/20/solid'
    import {
        StarIcon as StarOutlineIcon
    } from '@heroicons/vue/24/outline'
    import FileIcon from '@/Components/Files/FileIcon.vue';
    import Checkbox from '@/Components/Checkbox.vue';
    import DownloadFilesButton from '@/Components/Files/DownloadFilesButton.vue';
    import DeleteFilesButton from '@/Components/Files/DeleteFilesButton.vue';
    import {
        computed,
        onMounted,
        onUpdated,
        ref
    } from 'vue';
    import {
        httpGet,
        httpPost
    } from '@/Helper/Http-helper';
    import {
        showErrorNotification,
        showSuccessNotification
    } from '@/event-bus';

    // Uses
    const page = usePage()

    // Props & Emits
    const props = defineProps({
        files: Object,
        folder: Object,
        ancestors: Object
    })

    // Refs
    const allSelected = ref(false)
    const onlyFavorites = ref(false)
    const selected = ref({})
    const loadMoreIntersect = ref(null)
    const allFiles = ref({
        data: props.files.data,
        next: props.files.links.next
    })
    let params = null


    // Computed
    const selectedIds = computed(() => Object.entries(selected.value).filter(a => a[1]).map(a => a[0]))

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

    function onSelectAllChange() {
        allFiles.value.data.forEach(f => {
            selected.value[f.id] = allSelected.value
        })
    }

    function toggleFileSelect(file) {
        selected.value[file.id] = !selected.value[file.id]
        onSelectedCheckBoxChange(file)
    }

    function onSelectedCheckBoxChange(file) {
        if (!selected.value[file.id]) {
            allSelected.value = false
        } else {
            let checked = true
            for (let file of allFiles.value.data) {
                if (!selected.value[file.id]) {
                    checked = false
                    break
                }
            }

            allSelected.value = checked
        }
    }

    function onDelete() {
        allSelected.value = false
        selected.value = {}
    }

    function addRemoveFavorite(file) {
        httpPost(route('files.favorites'), {
            id: file.id
        }).then(() => {
            file.is_favourite = !file.is_favourite
            if (file.is_favourite) {
                showSuccessNotification('Selected file have been added to favourites.')
            } else {
                showSuccessNotification('Selected file have been removed from favourites.')
            }
        }).catch((e) => {
            showErrorNotification('Error: ' + e.error.message);
        })
    }

    function showOnlyFavorites() {
        if(onlyFavorites.value){
            params.set('favorites', 1)
        }else{
            params.delete('favorites')
        }
        router.get(window.location.pathname + '?' + params.toString())
    }

    // Hooks
    onUpdated(() => {
        allFiles.value = {
            data: props.files.data,
            next: props.files.links.next
        }
    })

    onMounted(() => {
        params = new URLSearchParams(window.location.search)
        onlyFavorites.value = params.get('favorites') === '1'

        const observer = new IntersectionObserver((entries) => entries.forEach(entry => entry.isIntersecting &&
            loadMore()), {
            rootMargin: '-250px 0px 0px 0px'
        })

        observer.observe(loadMoreIntersect.value)
    })
</script>

<style scoped>

</style>
