<template>
    <template v-if="'link' === as">
        <Link :class="computedClass">
            <slot />
        </Link>
    </template>
    <component v-else :is="as" :class="computedClass" :disabled="disabled">
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
    disabled: Boolean,
});

const computedClass = computed(() => {
    return [
        {
            lg: 'px-4 py-3',
            md: 'px-4 py-2 text-sm',
        }[props.size],
        'text-brand-dark bg-white rounded-md ring-1 ring-brand-secondary/[0.15] transition-shadow ease-in-out',
        'hover:text-brand-secondary',
        'focus:outline-none focus:ring-brand-secondary/50',
        'disabled:bg-gray-500',
        'dark:bg-brand-secondary/20 dark:text-white/80 dark:ring-brand-secondary/20 dark:hover:bg-brand-secondary/10',
        'dark:focus:text-white/60',
    ];
});
</script>
