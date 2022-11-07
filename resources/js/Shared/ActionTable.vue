<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                :class="[
                    'flex items-center text-brand-secondary/60 rounded-full hover:text-brand-dark/80',
                    'focus:bg-brand-primary/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-brand-primary/80',
                    'dark:focus:bg-brand-secondary/20 dark:hover:text-white/80 dark:focus:ring-offset-brand-dark',
                ]"
            >
                <span class="sr-only">Open options</span>
                <EllipsisVerticalIcon class="w-4 h-4" aria-hidden="true" />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems class="right-0 w-32 component-dropdown">
                <slot name="prepend-menu"></slot>
                <MenuItem v-if="editLink">
                    <Link :href="editLink" class="component-dropdown-item group font-normal">
                        <icon-tabler-edit
                            class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                            aria-hidden="true"
                        />Edit
                    </Link>
                </MenuItem>
                <MenuItem v-if="deleteLink">
                    <button
                        type="button"
                        @click="$emit('delete')"
                        class="component-dropdown-item group font-normal"
                    >
                        <icon-tabler-trash
                            class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                            aria-hidden="true"
                        />Delete
                    </button>
                </MenuItem>
                <MenuItem v-if="restoreLink">
                    <Link
                        :href="restoreLink"
                        as="button"
                        method="post"
                        class="component-dropdown-item group font-normal"
                    >
                        <icon-tabler-rotate-2
                            class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                            aria-hidden="true"
                        />Restore
                    </Link>
                </MenuItem>
                <slot name="append-menu"></slot>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';

import { Link } from '@inertiajs/inertia-vue3';

import { EllipsisVerticalIcon } from '@heroicons/vue/24/outline';

defineEmits(['delete']);

defineProps({
    editLink: [String, Object],
    restoreLink: [String, Object],
    deleteLink: Boolean,
});
</script>
