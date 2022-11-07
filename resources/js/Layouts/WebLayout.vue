<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';

import DropdownMenu from '@/Shared/DropdownMenu.vue';
import DropdownMenuLink from '@/Shared/DropdownMenuLink.vue';

const navigations = computed(() => usePage().props.value?.web?.navigations || []);
const tickers = computed(() => usePage().props.value?.web?.tickers || []);

import Logo from '@/Shared/Logo.vue';

import RunningText from '../Pages/Web/Partials/RunningText.vue';

defineProps({
    title: String,
    settings: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    Inertia.put(
        route('current-team.update'),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    Inertia.post(route('logout'));
};

import { useColorMode, useStorage } from '@vueuse/core';
const colorMode = useColorMode();
const colorModeProxy = useStorage('color-scheme', 'light');
const colorScheme = useStorage('vueuse-color-scheme', 'light');
colorScheme.value = 'light';
</script>

<template>
    <div>
        <Head :title="title" />

        <JetBanner />

        <div class="min-h-screen bg-gray-100">
            <div class="w-full bg-white">
                <RunningText class="" />
            </div>
            <nav class="border-gray-100 bg-brand-primary">
                <!-- Primary Navigation Menu -->
                <div class="px-12 mx-auto max-w-7xl sm:px-14 lg:px-8 2xl:px-0 text-brand-light">
                    <div class="flex justify-between w-full">
                        <div
                            class="flex space-x-4 font-medium text-brand-light text-md md:space-x-8 lg:space-x-16"
                        >
                            <!-- Logo -->
                            <div class="flex items-center py-10 shrink-0">
                                <Link :href="route('dashboard')">
                                    <Logo class="h-14 text-brand-light" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden py-10 space-x-8 shrink-0 sm:-my-px sm:flex sm:items-center"
                            >
                                <template v-for="navigation in navigations">
                                    <DropdownMenu
                                        v-if="navigation.has_group"
                                        :width="navigation.has_group ? 'full' : '48'"
                                        align="center"
                                        :open="true"
                                        :contentClasses="[
                                            'bg-brand-light py-4',
                                            navigation.has_group ? 'grid grid-cols-3' : '',
                                        ]"
                                        class="justify-center px-1 pt-1 text-sm font-medium leading-5 transition border-transparent hover:text-brand-light hover:border-gray-300 focus:outline-none focus:text-brand-light focus:border-gray-300"
                                    >
                                        <template #trigger>
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 transition border border-transparent hover:text-brand-light/80 focus:outline-none focus:ring-1 focus:bg-white/10 focus:ring-brand-secondary/10"
                                            >
                                                {{ navigation.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    viewBox="0 0 18 18"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M8.99999 9.879L12.7125 6.1665L13.773 7.227L8.99999 12L4.22699 7.227L5.28749 6.1665L8.99999 9.879Z"
                                                        fill="currentColor"
                                                    />
                                                </svg>
                                            </button>
                                        </template>
                                        <template #content>
                                            <div v-for="group in navigation.childs">
                                                <div class="px-8 py-2">
                                                    <div class="text-brand-primary">
                                                        {{ group.name }}
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-300">
                                                        {{ group.description }}
                                                    </div>
                                                </div>
                                                <ul class="px-2">
                                                    <li v-for="item in group.childs">
                                                        <DropdownMenuLink
                                                            :href="item.url"
                                                            :contentClasses="['text-brand-dark']"
                                                            >{{ item.name }}</DropdownMenuLink
                                                        >
                                                    </li>
                                                </ul>
                                            </div>
                                        </template>
                                    </DropdownMenu>
                                    <DropdownMenu
                                        v-else-if="navigation.has_child"
                                        :contentClasses="['bg-brand-light py-4']"
                                    >
                                        <template #trigger>
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 transition border border-transparent hover:text-brand-light/80 focus:outline-none focus:ring-1 focus:bg-white/10 focus:ring-brand-secondary/10"
                                            >
                                                {{ navigation.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    viewBox="0 0 18 18"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M8.99999 9.879L12.7125 6.1665L13.773 7.227L8.99999 12L4.22699 7.227L5.28749 6.1665L8.99999 9.879Z"
                                                        fill="currentColor"
                                                    />
                                                </svg>
                                            </button>
                                        </template>
                                        <template #content>
                                            <ul>
                                                <li v-for="item in navigation.childs">
                                                    <DropdownMenuLink
                                                        :href="item.url"
                                                        :contentClasses="['text-brand-dark']"
                                                        >{{ item.name }}</DropdownMenuLink
                                                    >
                                                </li>
                                            </ul>
                                        </template>
                                    </DropdownMenu>
                                    <Link
                                        v-else
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 transition border border-transparent hover:text-brand-light/80 focus:outline-none focus:ring-1 focus:bg-white/10 focus:ring-brand-secondary/10"
                                        :href="navigation.url"
                                        :active="route().current(navigation.url)"
                                    >
                                        <p class="mr-1">{{ navigation.name }}</p>
                                    </Link>
                                </template>
                            </div>
                        </div>
                        <div class="hidden sm:flex">
                            <JetNavLink
                                class="flex items-center justify-center group hover:text-brand-light/80"
                                href="#"
                                :active="route().current('dashboard')"
                            >
                                <span
                                    class="px-6 py-3 border border-brand-light group-hover:border-brand-light/80"
                                >
                                    Kontak Kami
                                </span>
                            </JetNavLink>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center -mr-2 sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 text-gray-400 transition rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500"
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                            >
                                <svg
                                    class="w-6 h-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <JetResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </JetResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="py-6 mx-auto max-w-7xl">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
