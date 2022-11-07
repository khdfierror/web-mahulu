<template>
    <template v-if="'link' === as">
        <Link :class="computedClass">
            <slot />
        </Link>
    </template>
    <component v-else :is="as" :class="computedClass">
        <slot />
    </component>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';

import { computed } from 'vue';

const props = defineProps({
    as: {
        type: String,
        default: 'button',
    },
    size: {
        type: String,
        default: 'md',
    },
});

const computedClass = computed(() => {
    return [
        {
            lg: 'px-4 py-3 font-semibold',
            md: 'px-4 py-2 font-semibold',
        }[props.size],
        'text-white bg-gray-600 rounded-md shadow',
        'hover:bg-gray-700',
        'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 focus:ring-offset-gray-800',
    ];
});
</script>
