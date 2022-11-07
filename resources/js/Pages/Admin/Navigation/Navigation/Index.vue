<template>
    <app-layout title="Navigation" menu="navigation">
        <template #header>
            <div>
                <div class="flex items-center">
                    <div class="flex-1">
                        <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                            Navigations
                        </h2>
                    </div>
                    <div class="inline-flex items-center">
                        <Menu as="div" class="relative inline-block mr-2 text-left">
                            <div>
                                <MenuButton
                                    :class="[
                                        'flex items-center text-brand-secondary/60 rounded-full',
                                        'hover:text-brand-dark/80 dark:hover:text-white/80',
                                        'focus:bg-brand-primary/10',
                                        'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-brand-primary/80',
                                        'dark:focus:ring-offset-brand-dark',
                                    ]"
                                >
                                    <span class="sr-only">Open options</span>
                                    <EllipsisVerticalIcon class="w-5 h-5" aria-hidden="true" />
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
                                <MenuItems class="right-0 w-48 origin-top-right component-dropdown">
                                    <div class="py-1">
                                        <MenuItem v-slot="{ active }">
                                            <button
                                                type="button"
                                                :class="['component-dropdown-item group w-full']"
                                            >
                                                <icon-tabler-settings
                                                    class="w-5 h-5 mr-2 text-brand-secondary group-hover:text-brand-primary"
                                                />
                                                Setting Navigations
                                            </button>
                                        </MenuItem>
                                        <!-- <MenuItem v-slot="{ active }">
                                            <Link
                                                :href="route('list.index')"
                                                :class="[
                                                    'component-dropdown-item group'
                                                ]"
                                            >
                                                <icon-heroicons-outline-user-group
                                                    class="w-5 h-5 mr-2 text-brand-secondary group-hover:text-brand-primary"
                                                />Navigation Lists
                                            </Link>
                                        </MenuItem> -->
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
                <div class="flex items-center mt-3">
                    <div class="flex-1">
                        <Filter :filters="filters" :params="params" />
                    </div>
                    <div class="inline-flex items-center ml-auto">
                        <ButtonSecondary
                            size="md"
                            as="link"
                            :href="route('kedeka::admin.navigation.navigation.create')"
                            class="text-sm"
                        >
                            <icon-tabler-edit class="w-5 h-5 mr-2 text-blue-500" />Create Navigation
                        </ButtonSecondary>
                    </div>
                </div>
            </div>
        </template>

        <div class="mt-6">
            <div class="inline-block min-w-full py-2 align-middle">
                <div
                    class="sm:rounded-lg ring-1 ring-brand-secondary/[0.05] bg-white dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
                >
                    <table
                        class="min-w-full border-separate sm:table-rounded-lg"
                        style="border-spacing: 0"
                    >
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:pl-6 lg:pl-8"
                                >
                                    Navigation
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 hidden border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 px-3 py-3.5 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:table-cell"
                                >
                                    URL
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 py-3.5 pr-4 pl-3 dark:text-slate-400 sm:pr-6 lg:pr-8"
                                >
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!navigation.data?.length">
                                <td
                                    colspan="4"
                                    class="py-6 pl-4 pr-3 text-sm text-center text-gray-300 border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pl-6 lg:pl-8"
                                >
                                    No data
                                </td>
                            </tr>
                            <template v-for="(item, index) in navigation.data">
                                <tr>
                                    <td
                                        :class="[
                                            'py-2 pl-4 pr-3 text-sm font-medium border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pl-6 lg:pl-8',
                                            item.deleted_at
                                                ? 'italic text-gray-400'
                                                : 'text-brand-dark dark:text-white',
                                        ]"
                                    >
                                        <div class="flex">{{ item.name }}</div>
                                        <div
                                            class="text-xs italic text-gray-300"
                                            v-if="item.deleted_at"
                                        >
                                            <UseTimeAgo
                                                v-slot="{ timeAgo }"
                                                :time="Date.parse(item.deleted_at)"
                                                >Deleted {{ timeAgo }}</UseTimeAgo
                                            >
                                        </div>
                                    </td>
                                    <td
                                        :class="[
                                            'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:table-cell',
                                            item.deleted_at
                                                ? 'italic text-gray-400'
                                                : 'text-brand-secondary dark:text-slate-400',
                                        ]"
                                    >
                                        <a
                                            :href="item.url"
                                            target="_blank"
                                            class="hover:text-brand-primary"
                                        >
                                            {{ item.url }}
                                        </a>
                                    </td>
                                    <td
                                        class="relative py-2 pl-3 pr-4 text-sm font-medium text-right border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pr-6 lg:pr-8"
                                    >
                                        <ActionTable
                                            :edit-link="
                                                route('kedeka::admin.navigation.navigation.edit', {
                                                    navigation: item.id,
                                                })
                                            "
                                            :delete-link="!item.deleted_at"
                                            @delete="() => (navigationBeingDeleted = item)"
                                            :restore-link="
                                                item.deleted_at
                                                    ? route('navigation.restore', {
                                                          navigation: item.id,
                                                      })
                                                    : null
                                            "
                                        >
                                            <template #append-menu>
                                                <div
                                                    class="my-1 -mx-1 border-b border-brand-secondary/10 dark:border-brand-secondary/20"
                                                />
                                                <MenuItem v-if="index !== 0">
                                                    <Link
                                                        :href="
                                                            route(
                                                                'kedeka::admin.navigation.navigation.move',
                                                                {
                                                                    navigation: item.id,
                                                                    direction: 'up',
                                                                }
                                                            )
                                                        "
                                                        as="button"
                                                        method="post"
                                                        class="font-normal component-dropdown-item group"
                                                    >
                                                        <icon-tabler-chevrons-up
                                                            class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                                                            aria-hidden="true"
                                                        />Up
                                                    </Link>
                                                </MenuItem>
                                                <MenuItem v-if="navigation.data[index + 1]">
                                                    <Link
                                                        :href="
                                                            route(
                                                                'kedeka::admin.navigation.navigation.move',
                                                                {
                                                                    navigation: item.id,
                                                                    direction: 'down',
                                                                }
                                                            )
                                                        "
                                                        as="button"
                                                        method="post"
                                                        class="font-normal component-dropdown-item group"
                                                    >
                                                        <icon-tabler-chevrons-down
                                                            class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                                                            aria-hidden="true"
                                                        />Down
                                                    </Link>
                                                </MenuItem>
                                            </template>
                                        </ActionTable>
                                    </td>
                                </tr>
                                <template v-for="(subitem, subindex) in item.childs">
                                    <tr>
                                        <td
                                            :class="[
                                                'py-2 pl-4 pr-3 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pl-6 lg:pl-8',
                                                subitem.deleted_at
                                                    ? 'italic text-gray-400'
                                                    : 'text-brand-dark dark:text-white',
                                            ]"
                                        >
                                            <div class="flex">
                                                <div class="pr-2 -my-2">
                                                    <div
                                                        class="w-8 border-b border-l border-gray-100 dark:border-brand-secondary/20 h-1/2"
                                                    >
                                                        &nbsp;
                                                    </div>
                                                    <div
                                                        v-if="item.childs[subindex + 1]"
                                                        class="w-8 border-l border-gray-100 dark:border-brand-secondary/20 h-1/2"
                                                    >
                                                        &nbsp;
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="flex items-center">
                                                        <div class="font-medium">
                                                            {{ subitem.name }}
                                                        </div>
                                                        <div class="text-xs ml-3 text-brand-gray">
                                                            {{ subitem.description }}
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="text-xs italic text-gray-300"
                                                        v-if="subitem.deleted_at"
                                                    >
                                                        <UseTimeAgo
                                                            v-slot="{ timeAgo }"
                                                            :time="Date.parse(subitem.deleted_at)"
                                                            >Deleted {{ timeAgo }}
                                                        </UseTimeAgo>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            :class="[
                                                'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:table-cell',
                                                subitem.deleted_at
                                                    ? 'italic text-gray-400'
                                                    : 'text-brand-secondary dark:text-slate-400',
                                            ]"
                                        >
                                            <a
                                                :href="subitem.url"
                                                target="_blank"
                                                class="hover:text-brand-primary"
                                            >
                                                {{ subitem.url }}
                                            </a>
                                        </td>
                                        <td
                                            class="relative py-2 pl-3 pr-4 text-sm font-medium text-right border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pr-6 lg:pr-8"
                                        >
                                            <ActionTable
                                                :edit-link="
                                                    route(
                                                        'kedeka::admin.navigation.navigation.edit',
                                                        {
                                                            navigation: subitem.id,
                                                        }
                                                    )
                                                "
                                                :delete-link="!subitem.deleted_at"
                                                @delete="() => (navigationBeingDeleted = item)"
                                                :restore-link="
                                                    subitem.deleted_at
                                                        ? route('navigation.restore', {
                                                              navigation: subitem.id,
                                                          })
                                                        : null
                                                "
                                            >
                                                <template #append-menu>
                                                    <div
                                                        class="my-1 -mx-1 border-b border-brand-secondary/10 dark:border-brand-secondary/20"
                                                    />
                                                    <MenuItem v-if="subindex !== 0">
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'kedeka::admin.navigation.navigation.move',
                                                                    {
                                                                        navigation: subitem.id,
                                                                        direction: 'up',
                                                                    }
                                                                )
                                                            "
                                                            as="button"
                                                            method="post"
                                                            class="font-normal component-dropdown-item group"
                                                        >
                                                            <icon-tabler-chevrons-up
                                                                class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                                                                aria-hidden="true"
                                                            />Up
                                                        </Link>
                                                    </MenuItem>
                                                    <MenuItem v-if="item.childs[subindex + 1]">
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'kedeka::admin.navigation.navigation.move',
                                                                    {
                                                                        navigation: subitem.id,
                                                                        direction: 'down',
                                                                    }
                                                                )
                                                            "
                                                            as="button"
                                                            method="post"
                                                            class="font-normal component-dropdown-item group"
                                                        >
                                                            <icon-tabler-chevrons-down
                                                                class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                                                                aria-hidden="true"
                                                            />Down
                                                        </Link>
                                                    </MenuItem>
                                                </template>
                                            </ActionTable>
                                        </td>
                                    </tr>
                                    <tr v-for="(subitem2, subindex2) in subitem.childs">
                                        <td
                                            :class="[
                                                'py-2 pl-4 pr-3 text-sm font-medium border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pl-6 lg:pl-8',
                                                subitem2.deleted_at
                                                    ? 'italic text-gray-400'
                                                    : 'text-brand-dark dark:text-white',
                                            ]"
                                        >
                                            <div class="flex">
                                                <div class="pr-2 -my-2">
                                                    <div
                                                        class="ml-8 w-8 border-b border-l border-gray-100 dark:border-brand-secondary/20 h-1/2"
                                                    >
                                                        &nbsp;
                                                    </div>
                                                    <div
                                                        v-if="subitem.childs[subitem2 + 1]"
                                                        class="w-8 border-l border-gray-100 dark:border-brand-secondary/20 h-1/2"
                                                    >
                                                        &nbsp;
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="flex">
                                                        {{ subitem2.name }}
                                                    </div>
                                                    <div
                                                        class="text-xs italic text-gray-300"
                                                        v-if="subitem2.deleted_at"
                                                    >
                                                        <UseTimeAgo
                                                            v-slot="{ timeAgo }"
                                                            :time="Date.parse(subitem2.deleted_at)"
                                                            >Deleted {{ timeAgo }}
                                                        </UseTimeAgo>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            :class="[
                                                'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:table-cell',
                                                subitem2.deleted_at
                                                    ? 'italic text-gray-400'
                                                    : 'text-brand-secondary dark:text-slate-400',
                                            ]"
                                        >
                                            <a
                                                :href="subitem2.url"
                                                target="_blank"
                                                class="hover:text-brand-primary"
                                            >
                                                {{ subitem2.url }}
                                            </a>
                                        </td>
                                        <td
                                            class="relative py-2 pl-3 pr-4 text-sm font-medium text-right border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pr-6 lg:pr-8"
                                        >
                                            <ActionTable
                                                :edit-link="
                                                    route(
                                                        'kedeka::admin.navigation.navigation.edit',
                                                        {
                                                            navigation: subitem2.id,
                                                        }
                                                    )
                                                "
                                                :delete-link="!subitem2.deleted_at"
                                                @delete="() => (navigationBeingDeleted = item)"
                                                :restore-link="
                                                    subitem2.deleted_at
                                                        ? route('navigation.restore', {
                                                              navigation: subitem2.id,
                                                          })
                                                        : null
                                                "
                                            >
                                                <template #append-menu>
                                                    <div
                                                        class="my-1 -mx-1 border-b border-brand-secondary/10 dark:border-brand-secondary/20"
                                                    />
                                                    <MenuItem v-if="subindex !== 0">
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'kedeka::admin.navigation.navigation.move',
                                                                    {
                                                                        navigation: subitem2.id,
                                                                        direction: 'up',
                                                                    }
                                                                )
                                                            "
                                                            as="button"
                                                            method="post"
                                                            class="font-normal component-dropdown-item group"
                                                        >
                                                            <icon-tabler-chevrons-up
                                                                class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                                                                aria-hidden="true"
                                                            />Up
                                                        </Link>
                                                    </MenuItem>
                                                    <MenuItem v-if="item.childs[subindex + 1]">
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'kedeka::admin.navigation.navigation.move',
                                                                    {
                                                                        navigation: subitem2.id,
                                                                        direction: 'down',
                                                                    }
                                                                )
                                                            "
                                                            as="button"
                                                            method="post"
                                                            class="font-normal component-dropdown-item group"
                                                        >
                                                            <icon-tabler-chevrons-down
                                                                class="w-5 h-5 mr-3 text-brand-secondary/60 group-hover:text-brand-secondary"
                                                                aria-hidden="true"
                                                            />Down
                                                        </Link>
                                                    </MenuItem>
                                                </template>
                                            </ActionTable>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <Pagination
                size="md"
                :params="filters"
                :links="navigation?.links"
                :prev="navigation?.prev"
                :next="navigation?.next"
                :only="['navigation', 'filters', 'inertiajs']"
            />
        </div>
        <jet-confirmation-modal
            :show="navigationBeingDeleted !== null"
            @close="navigationBeingDeleted = null"
        >
            <template #title>Delete Navigation {{ navigationBeingDeleted?.title }}</template>

            <template #content>Are you sure you would like to delete this Navigation?</template>

            <template #footer>
                <ButtonWhite @click="navigationBeingDeleted = null">Cancel</ButtonWhite>

                <ButtonDanger
                    class="ml-3"
                    @click="deleteNavigation"
                    :class="{ 'opacity-25': deleteNavigationForm.processing }"
                    :disabled="deleteNavigationForm.processing"
                    >Delete</ButtonDanger
                >
            </template>
        </jet-confirmation-modal>
    </app-layout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonSecondary from '@/Shared/ButtonSecondary.vue';
import ButtonWhite from '@/Shared/ButtonWhite.vue';
import ButtonDanger from '@/Shared/ButtonDanger.vue';
import ActionTable from '@/Shared/ActionTable.vue';
import Pagination from '@/Shared/Pagination.vue';
import Filter from './Partials/Filter.vue';

import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue';
import { ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EllipsisVerticalIcon } from '@heroicons/vue/24/solid';
import { UseTimeAgo } from '@vueuse/components';

import dayjs from 'dayjs';
import axios from 'axios';

const tenant = usePage().props.value.tenant;
const props = defineProps({
    navigation: Object,
    filters: [Array, Object],
    params: [Array, Object],
});

const dateFormat = (date) => dayjs(date).format('DD MMMM YYYY');
const openImportNavigation = ref(false);

const navigationBeingDeleted = ref(null);
const deleteNavigationForm = useForm();

const deleteNavigation = () =>
    deleteNavigationForm.delete(
        route('kedeka::admin.navigation.navigation.destroy', {
            navigation: navigationBeingDeleted.value?.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (navigationBeingDeleted.value = null),
        }
    );
</script>
