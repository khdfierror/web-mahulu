<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    href: String,
    as: String,
    contentClasses: {
        type: Array,
        default: () => ['text-white'],
    },
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            type="submit"
            class="block w-full px-6 py-2 text-sm leading-5 text-left hover:bg-black/10 focus:outline-none focus:bg-black/10 transition"
            :class="contentClasses"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            class="block px-6 py-2 text-sm leading-5 hover:bg-black/10 focus:outline-none focus:bg-black/10 transition"
            :class="contentClasses"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            class="block px-6 py-2 text-sm leading-5 hover:bg-black/10 focus:outline-none focus:bg-black/10 transition"
            :class="contentClasses"
        >
            <slot />
        </Link>
    </div>
</template>
