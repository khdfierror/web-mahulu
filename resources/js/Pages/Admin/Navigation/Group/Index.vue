<template>
    <app-layout title="Group" menu="navigation.group">
        <template #header>
            <div>
                <div class="flex items-center">
                    <div class="flex-1">
                        <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                            Navigation Groups
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
                                                />Setting Categories
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
                                                />Group Lists
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
                            :href="route('kedeka::admin.navigation.group.create')"
                            class="text-sm"
                        >
                            <icon-tabler-circle-plus class="w-5 h-5 mr-2 text-blue-500" />Add Group
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
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 hidden border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 px-3 py-3.5 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:table-cell"
                                >
                                    Description
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 hidden border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 px-3 py-3.5 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:table-cell"
                                >
                                    Last Updated
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
                            <tr v-if="!group.data?.length">
                                <td
                                    colspan="4"
                                    class="py-6 pl-4 pr-3 text-sm text-center text-gray-300 border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pl-6 lg:pl-8"
                                >
                                    No data
                                </td>
                            </tr>
                            <tr v-for="item in group.data">
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
                                    {{ item.description }}
                                </td>
                                <td
                                    :class="[
                                        'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:table-cell',
                                        item.deleted_at
                                            ? 'italic text-gray-400'
                                            : 'text-brand-secondary dark:text-slate-400',
                                    ]"
                                >
                                    {{ item.updated_at.short }}
                                </td>
                                <td
                                    class="relative py-2 pl-3 pr-4 text-sm font-medium text-right border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pr-6 lg:pr-8"
                                >
                                    <ActionTable
                                        :edit-link="
                                            route('kedeka::admin.navigation.group.edit', {
                                                group: item.id,
                                            })
                                        "
                                        :delete-link="!item.deleted_at"
                                        @delete="() => (groupBeingDeleted = item)"
                                        :restore-link="
                                            item.deleted_at
                                                ? route('kedeka::admin.navigation.group.restore', {
                                                      group: item.id,
                                                  })
                                                : null
                                        "
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <Pagination
                size="md"
                :params="filters"
                :links="group?.links"
                :prev="group?.prev"
                :next="group?.next"
                :only="['group', 'filters', 'inertiajs']"
            />
        </div>
        <jet-confirmation-modal
            :show="groupBeingDeleted !== null"
            @close="groupBeingDeleted = null"
        >
            <template #title>Delete Group {{ groupBeingDeleted?.title }}</template>

            <template #content>Are you sure you would like to delete this Group?</template>

            <template #footer>
                <ButtonWhite @click="groupBeingDeleted = null">Cancel</ButtonWhite>

                <ButtonDanger
                    class="ml-3"
                    @click="deleteGroup"
                    :class="{ 'opacity-25': deleteGroupForm.processing }"
                    :disabled="deleteGroupForm.processing"
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

const props = defineProps({
    group: Object,
    filters: [Array, Object],
    params: [Array, Object],
});

const dateFormat = (date) => dayjs(date).format('DD MMMM YYYY');
const openImportGroup = ref(false);

const groupBeingDeleted = ref(null);
const deleteGroupForm = useForm();

const deleteGroup = () =>
    deleteGroupForm.delete(
        route('kedeka::admin.navigation.group.destroy', {
            group: groupBeingDeleted.value?.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (groupBeingDeleted.value = null),
        }
    );
</script>
