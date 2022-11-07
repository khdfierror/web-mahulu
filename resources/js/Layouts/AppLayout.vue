<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';

import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Popover,
    PopoverButton,
    PopoverPanel,
} from '@headlessui/vue';
import Search from '@/Shared/Search.vue';
import IconDashboardNavigation from '@/Shared/IconDashboardNavigation.vue';

const userNavigation = [
    { name: 'Account', href: route('dashboard'), icon: 'account' },
    // { name: "Account", href: route("account.show"), icon: 'account' },
];

import { useColorMode, useStorage } from '@vueuse/core';
const colorMode = useColorMode();
const colorModeProxy = useStorage('color-scheme', 'auto');
watch(
    () => colorModeProxy.value,
    (value) => (colorMode.value = value)
);

defineProps({
    title: String,
    menu: String,
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
</script>

<template>
    <div>
        <Head :title="title" />

        <Popover as="template" v-slot="{ open }">
            <header
                :class="[
                    open ? 'fixed inset-0 z-40 overflow-y-auto' : '',
                    'bg-white dark:bg-brand-secondary/20 lg:static lg:overflow-y-visible border border-brand-secondary/10',
                ]"
            >
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="relative flex justify-between xl:grid xl:grid-cols-12 lg:gap-8">
                        <div class="flex items-center lg:static xl:col-span-2">
                            <div class="flex items-center flex-shrink-0 py-4 sm:py-0">
                                <Link :href="route('dashboard')" class="flex">
                                    <Logo alt class="h-11 text-[#4D4D4D] dark:text-white" />
                                </Link>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 md:px-8 lg:px-0 xl:col-span-6">
                            <div class="hidden max-w-xs py-3 sm:block">
                                <Search />
                            </div>
                        </div>
                        <div
                            class="flex items-center md:absolute md:right-0 md:inset-y-0 lg:hidden"
                        >
                            <!-- Mobile menu button -->
                            <PopoverButton
                                class="inline-flex items-center justify-center p-2 -mx-2 rounded-full text-brand-secondary/60 hover:bg-gray-100 hover:text-brand-secondary focus:outline-none focus:ring-2 focus:ring-inset focus:ring-brand-secondary"
                            >
                                <span class="sr-only">Open menu</span>
                                <IconHeroiconsOutlineMenu
                                    v-if="!open"
                                    class="block w-6 h-6"
                                    aria-hidden="true"
                                />
                                <IconHeroiconsOutlineX
                                    v-else
                                    class="block w-6 h-6"
                                    aria-hidden="true"
                                />
                            </PopoverButton>
                        </div>
                        <div class="hidden lg:flex lg:items-center lg:justify-end xl:col-span-4">
                            <Menu as="div" class="relative hidden text-left md:inline-block">
                                <div>
                                    <MenuButton
                                        :class="[
                                            'flex-shrink-0 p-2 ml-5 text-brand-primary bg-white rounded-full hover:text-brand-primary/80',
                                            'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary',
                                            'dark:bg-brand-secondary/20 dark:focus:ring-offset-brand-dark',
                                        ]"
                                    >
                                        <icon-tabler-sun
                                            v-if="colorModeProxy === 'light'"
                                            class="w-5 h-5"
                                        />
                                        <icon-tabler-moon-stars
                                            v-if="colorModeProxy === 'dark'"
                                            class="w-5 h-5"
                                        />
                                        <icon-tabler-device-desktop
                                            v-if="colorModeProxy === 'auto'"
                                            class="w-5 h-5"
                                        />
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
                                        <MenuItem>
                                            <button
                                                type="button"
                                                @click="colorModeProxy = 'light'"
                                                class="text-left component-dropdown-item"
                                            >
                                                <icon-tabler-sun
                                                    :class="[
                                                        'text-brand-primary',
                                                        'hover:text-white/80',
                                                        'flex-shrink-0 -ml-1 mr-3 h-5 w-5',
                                                    ]"
                                                    aria-hidden="true"
                                                />
                                                Light
                                            </button>
                                        </MenuItem>
                                        <MenuItem>
                                            <button
                                                type="button"
                                                @click="colorModeProxy = 'dark'"
                                                class="text-left component-dropdown-item"
                                            >
                                                <icon-tabler-moon-stars
                                                    :class="[
                                                        'text-brand-primary',
                                                        'hover:text-white/80',
                                                        'flex-shrink-0 -ml-1 mr-3 h-5 w-5',
                                                    ]"
                                                    aria-hidden="true"
                                                />
                                                Dark
                                            </button>
                                        </MenuItem>
                                        <MenuItem>
                                            <button
                                                type="button"
                                                @click="colorModeProxy = 'auto'"
                                                class="text-left component-dropdown-item"
                                            >
                                                <icon-tabler-device-desktop
                                                    :class="[
                                                        'text-brand-primary',
                                                        'hover:text-white/80',
                                                        'flex-shrink-0 -ml-1 mr-3 h-5 w-5',
                                                    ]"
                                                    aria-hidden="true"
                                                />
                                                System
                                            </button>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>

                            <Menu as="div" class="relative hidden ml-5 text-left md:inline-block">
                                <div>
                                    <MenuButton
                                        :class="[
                                            'flex bg-white items-center rounded-full',
                                            'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary',
                                            'dark:bg-brand-secondary/20 dark:text-slate-400 dark:focus:ring-offset-brand-dark',
                                        ]"
                                    >
                                        <span class="sr-only">Open user menu</span>
                                        <img
                                            class="w-8 h-8 rounded-full"
                                            :src="$page.props.user.profile_photo_url"
                                            alt
                                        />
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
                                    <MenuItems class="right-0 w-32 component-dropdown absolute">
                                        <MenuItem
                                            v-for="item in userNavigation"
                                            :key="item.name"
                                            v-slot="{ active }"
                                        >
                                            <Link
                                                :href="item.href"
                                                class="text-left component-dropdown-item"
                                            >
                                                <IconDashboardNavigation
                                                    :name="item.icon"
                                                    :class="[
                                                        active ? '' : 'text-brand-primary',
                                                        'flex-shrink-0 -ml-1 mr-3 h-5 w-5',
                                                    ]"
                                                    aria-hidden="true"
                                                />
                                                {{ item.name }}
                                            </Link>
                                        </MenuItem>
                                        <form @submit.prevent="logout">
                                            <MenuItem v-slot="{ active }">
                                                <button
                                                    class="w-full text-left component-dropdown-item"
                                                >
                                                    <icon-heroicons-outline-logout
                                                        :class="[
                                                            active ? '' : 'text-brand-primary',
                                                            'flex-shrink-0 -ml-1 mr-3 h-5 w-5',
                                                        ]"
                                                        aria-hidden="true"
                                                    />Log Out
                                                </button>
                                            </MenuItem>
                                        </form>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                </div>

                <PopoverPanel as="nav" class="lg:hidden" aria-label="Global">
                    <div class="max-w-3xl px-2 pt-2 pb-3 mx-auto sm:hidden">
                        <Search />
                    </div>

                    <div class="max-w-3xl px-2 pt-2 pb-3 mx-auto space-y-1 sm:px-4">
                        <component
                            :is="item.target ? 'a' : Link"
                            v-for="item in $page.props.navigations"
                            :key="item.name"
                            :href="item.href"
                            :target="item.target"
                            :aria-current="item.key === menu ? 'page' : undefined"
                            :class="[
                                item.key === menu
                                    ? 'bg-brand-primary/10 text-brand-primary font-medium'
                                    : 'text-brand-secondary hover:bg-brand-secondary/10 hover:text-brand-dark/60',
                                'flex items-center px-3 py-2 rounded-md',
                            ]"
                            >{{ item.name }}</component
                        >
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center max-w-3xl px-4 mx-auto sm:px-6">
                            <div class="flex-shrink-0">
                                <img
                                    class="w-10 h-10 rounded-full"
                                    :src="$page.props.user.profile_photo_url"
                                    alt
                                />
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-brand-dark">
                                    {{ $page.props.user.name }}
                                </div>
                                <div class="text-sm text-brand-secondary">
                                    {{ $page.props.user.email }}
                                </div>
                            </div>
                            <button
                                type="button"
                                class="flex-shrink-0 p-1 ml-auto bg-white rounded-full text-brand-secondary/60 hover:text-brand-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary"
                            >
                                <span class="sr-only">View notifications</span>
                                <IconHeroiconsOutlineBell class="w-6 h-6" aria-hidden="true" />
                            </button>
                        </div>
                        <div class="max-w-3xl px-2 mx-auto mt-3 space-y-1 sm:px-4">
                            <a
                                v-for="item in userNavigation"
                                :key="item.name"
                                :href="item.href"
                                class="block px-3 py-2 text-base font-medium rounded-md text-brand-secondary hover:bg-gray-50 hover:text-gray-900"
                                >{{ item.name }}</a
                            >

                            <form @submit.prevent="logout">
                                <button
                                    :class="[
                                        'block px-3 py-2 text-base w-full text-left font-medium text-brand-secondary rounded-md hover:bg-gray-50 hover:text-gray-900',
                                    ]"
                                >
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </PopoverPanel>
            </header>
        </Popover>

        <Banner />

        <div class="py-10">
            <div
                class="max-w-3xl mx-auto sm:px-6 lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-12 lg:gap-8"
            >
                <div class="hidden lg:block lg:col-span-3 xl:col-span-2">
                    <nav aria-label="Sidebar" class="sticky divide-y divide-gray-300 top-4">
                        <div class="pb-8 space-y-1">
                            <template v-for="item in $page.props.navigations">
                                <div v-if="item.type === 'separator'" class="py-4">
                                    <div class="border-b border-brand-secondary/10" />
                                </div>
                                <div
                                    v-else-if="item.type === 'label'"
                                    class="pt-4 pl-3 text-xs font-medium tracking-wider uppercase"
                                >
                                    {{ item.label }}
                                </div>
                                <component
                                    :is="item.target ? 'a' : Link"
                                    v-else-if="item.type === 'link'"
                                    :key="item.name"
                                    :href="item.href"
                                    :target="item.target"
                                    :class="[
                                        item.key === menu
                                            ? 'bg-brand-secondary text-white dark:bg-brand-primary/30 font-medium dark:ring-brand-primary/50'
                                            : 'text-slate-600 hover:text-brand-secondary hover:bg-brand-secondary/10 hover:ring-brand-secondary/20 dark:hover:bg-brand-primary/10 dark:hover:ring-brand-primary/50 dark:text-white/80 dark:hover:text-white',
                                        'group flex items-center transition-colors px-3 py-2 ring-1 ring-transparent text-sm rounded-lg',
                                    ]"
                                    :aria-current="item.key === menu ? 'page' : undefined"
                                >
                                    <IconDashboardNavigation
                                        :name="item.icon"
                                        :class="[
                                            item.key === menu
                                                ? 'dark:text-brand-primary'
                                                : 'text-brand-secondary dark:text-brand-primary/80 group-hover:text-brand-primary',
                                            'flex-shrink-0 transition-colors -ml-1 mr-3 h-6 w-6',
                                        ]"
                                        aria-hidden="true"
                                    />
                                    <span class="truncate">{{ item.name }}</span>
                                </component>
                            </template>
                        </div>
                    </nav>
                </div>
                <main class="lg:col-span-9 xl:col-span-10">
                    <div class="sticky top-6">
                        <slot name="header" />
                        <slot />
                        <div class="px-4 mt-8 sm:px-0">
                            <div class="flex items-center text-xs text-brand-secondary">
                                &copy; 2022 Balai Pelestarian Cagar Budaya Kalimantan. All Rights
                                Reserved.
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
