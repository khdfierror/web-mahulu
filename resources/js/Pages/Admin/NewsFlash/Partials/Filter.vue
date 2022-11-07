<template>
    <div class="flex">
        <label
            class="relative flex items-center text-brand-secondary/60 focus-within:text-brand-secondary"
        >
            <span class="absolute inset-0 flex items-center">
                <icon-heroicons-outline-search class="h-4 ml-4" />
                <icon-heroicons-outline-x
                    v-if="search"
                    @click="clearSearch"
                    class="h-3.5 mr-4 ml-auto cursor-pointer"
                />
            </span>
            <input
                type="text"
                placeholder="Search"
                autocomplete="off"
                autocorrect="off"
                v-model="search"
                class="filter-input px-10"
            />
        </label>
    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Inertia } from '@inertiajs/inertia';
import { computed, watch, reactive, ref } from 'vue';
import find from 'lodash/find';
import pickBy from 'lodash/pickBy';
import { watchDebounced } from '@vueuse/core';

const props = defineProps({
    filters: Object,
    params: Object,
});

const listOptionLabel = computed(() => {
    return find(props.params?.lists, { id: form.list })?.label;
});

const form = reactive({
    search: props.filters?.search || null,
    list: props.filters?.list || null,
});

const search = ref(props.filters?.search || null);

const clearSearch = () => {
    search.value = null;
    form.search = null;
};

watchDebounced(search, (value) => (form.search = value), { debounce: 300 });

watch(
    () => form,
    (value) => {
        let filters = pickBy(value);
        if (Object.keys(filters).length === 0) {
            filters = { remember: 'forget' };
        }

        submit(filters);
    },
    {
        deep: true,
    }
);

const submit = (query) =>
    Inertia.get(route('kedeka::admin.news-flash.index'), query, { preserveState: true });
</script>
