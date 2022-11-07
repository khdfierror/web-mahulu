<template>
    <div
        v-if="!link?.url || link?.active"
        :class="['mb-1 mx-1 btn-paginate inline-flex items-center disabled', buttonClass]"
    >
        <slot :label="link?.label">
            <span v-html="link?.label" />
        </slot>
    </div>
    <Link
        preserve-state
        :only="only"
        v-else
        :class="[
            'mb-1 mx-1 btn-paginate inline-flex items-center',
            link?.active ? 'active' : '',
            buttonClass,
        ]"
        :href="link?.url"
    >
        <slot :label="link?.label">
            <span v-html="link?.label" />
        </slot>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';

const props = defineProps({
    size: {
        type: String,
        default: 'lg',
    },
    link: Object,
    only: {
        type: Array,
        default: [],
    },
});

const buttonClass = computed(
    () =>
        ({
            sm: 'px-2 py-1 text-2xs',
            md: 'px-3 py-2 text-xs',
            lg: 'px-4 py-3 text-sm',
        }[props.size])
);
</script>
