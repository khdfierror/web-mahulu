<template>
    <div class="flex items-center justify-between mt-6 -mx-1 -mb-1">
        <PaginationButton :only="only" :size="size" :link="prev" v-slot="{ label }">
            <ChevronLeftIcon :class="iconSizeClass" />
            <span class="sr-only">{{ label }}</span>
        </PaginationButton>
        <div class="hidden md:block">
            <PaginationButton
                :only="only"
                :size="size"
                :link="link"
                v-for="(link, index, key) in links"
                :key="`paginate-${key}`"
            />
        </div>
        <div class="md:hidden">
            <SelectInput :size="size" v-model="linkSelect" :options="linkOptions" />
        </div>
        <PaginationButton :only="only" :size="size" :link="next" v-slot="{ label }">
            <ChevronRightIcon :class="iconSizeClass" />
            <span class="sr-only">{{ label }}</span>
        </PaginationButton>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import PaginationButton from './PaginationButton.vue';
import SelectInput from './SelectInput.vue';
import { Inertia } from '@inertiajs/inertia';
import find from 'lodash/find';

import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    size: {
        type: String,
        default: 'lg',
    },
    params: Object,
    links: [Array],
    prev: Object,
    next: Object,
    only: {
        type: Array,
        default: [],
    },
});

const linkSelect = computed({
    get: () => find(props.links, { active: true })?.url,
    set: (url) =>
        Inertia.visit(url, {
            preserveState: true,
            only: props.only,
        }),
});

const linkOptions = computed(() =>
    props.links
        ?.filter((i) => i.url)
        .map((i) => ({
            id: i.url,
            label: `Halaman ${i.label}`,
        }))
);

const iconSizeClass = computed(
    () =>
        ({
            sm: 'w-3 h-3',
            md: 'w-4 h-4',
            lg: 'w-5 h-5',
        }[props.size])
);
</script>
