<template>
    <template v-if="'link' === as">
        <Link :class="computedClass" :disabled="loading">
            <slot />
        </Link>
    </template>
    <component v-else :is="as" :class="computedClass" :disabled="disabled || loading">
        <svg
            v-if="loading"
            class="w-5 h-5 mr-3 -ml-1 text-gray-600 animate-spin"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            />
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            />
        </svg>
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
    ringBg: {
        type: String,
        default: 'light',
    },
    loading: Boolean,
    disabled: Boolean,
});

const computedClass = computed(() => {
    return [
        {
            lg: 'px-4 py-3',
            md: 'px-4 py-2 text-sm',
        }[props.size],
        'btn-secondary transition-all ease-in-out tracking-wide',
        'hover:bg-opacity-50',
        'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary',
        props.ringBg === 'light' ? 'focus:ring-offset-gray-100' : '',
        props.ringBg === 'dark' ? 'focus:ring-offset-gray-900' : '',
        'dark:focus:ring-offset-brand-dark',
        'disabled:text-gray-400 disabled:cursor-not-allowed',
    ];
});
</script>
