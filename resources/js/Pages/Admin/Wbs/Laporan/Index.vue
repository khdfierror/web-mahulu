<template>
    <app-layout :title="params?.title" menu="wbs-report">
        <template #header>
            <div>
                <div class="flex items-center">
                    <div class="flex-1">
                        <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                            {{ params?.title }}
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
                                                />Setting {{ params?.title }}
                                            </button>
                                        </MenuItem>
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
                        <!-- <ButtonSecondary
                            size="md"
                            as="link"
                            :href="route('admin.wbs.laporan.create')"
                            class="text-sm"
                        >
                            <icon-tabler-edit class="w-5 h-5 mr-2 text-blue-500" />Create
                            {{ params?.title }}
                        </ButtonSecondary> -->
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
                                    <div>Nama / NIP/NIK</div>
                                    <div>Email / Whatsapp</div>
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:pl-6 lg:pl-8"
                                >
                                    What
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:pl-6 lg:pl-8"
                                >
                                    Where
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:pl-6 lg:pl-8"
                                >
                                    When
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:pl-6 lg:pl-8"
                                >
                                    Who
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:pl-6 lg:pl-8"
                                >
                                    How
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 hidden border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 px-3 py-3.5 text-left text-sm font-normal text-brand-secondary dark:text-slate-400 sm:table-cell"
                                >
                                    Last Update
                                </th>
                                <th
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-100 dark:border-brand-secondary/20 bg-opacity-75 py-3.5 pr-4 pl-3 dark:text-white sm:pr-6 lg:pr-8"
                                >
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!laporan.data?.length">
                                <td
                                    colspan="4"
                                    class="py-6 pl-4 pr-3 text-sm text-center text-gray-300 border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pl-6 lg:pl-8"
                                >
                                    No data
                                </td>
                            </tr>
                            <tr v-for="item in laporan.data">
                                <td
                                    :class="[
                                        'py-2 pl-4 pr-3 text-sm font-medium border-b border-gray-100 dark:border-brand-secondary/20 whitespace-normal sm:pl-6 lg:pl-8',
                                        item.deleted_at
                                            ? 'italic text-gray-400'
                                            : 'text-brand-dark dark:text-white',
                                    ]"
                                >
                                    <div class="flex">{{ item.pelapor?.nama }}</div>
                                    <div
                                        class="text-xs italic dark:text-gray-300 text-brand-dark/50"
                                    >
                                        {{ item.pelapor?.nomor_identitas }}
                                    </div>
                                    <div
                                        class="text-xs italic dark:text-gray-300 text-brand-dark/50"
                                    >
                                        {{ item.pelapor?.email }}
                                    </div>
                                    <div
                                        class="text-xs italic dark:text-gray-300 text-brand-dark/50"
                                    >
                                        {{ item.pelapor?.whatsapp }}
                                    </div>
                                    <div
                                        class="text-xs italic dark:text-gray-300 text-brand-dark560"
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
                                        'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-normal sm:table-cell',
                                        item.deleted_at
                                            ? 'italic text-gray-400'
                                            : 'text-brand-secondary dark:text-brand-secondary',
                                    ]"
                                >
                                    {{ item.what }}
                                </td>
                                <td
                                    :class="[
                                        'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-normal sm:table-cell',
                                        item.deleted_at
                                            ? 'italic text-gray-400'
                                            : 'text-brand-secondary dark:text-brand-secondary',
                                    ]"
                                >
                                    {{ item.where }}
                                </td>
                                <td
                                    :class="[
                                        'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-normal sm:table-cell',
                                        item.deleted_at
                                            ? 'italic text-gray-400'
                                            : 'text-brand-secondary dark:text-brand-secondary',
                                    ]"
                                >
                                    {{ item.when }}
                                </td>
                                <td
                                    :class="[
                                        'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-normal sm:table-cell',
                                        item.deleted_at
                                            ? 'italic text-gray-400'
                                            : 'text-brand-secondary dark:text-brand-secondary',
                                    ]"
                                >
                                    {{ item.who }}
                                </td>
                                <td
                                    :class="[
                                        'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-normal sm:table-cell',
                                        item.deleted_at
                                            ? 'italic text-gray-400'
                                            : 'text-brand-secondary dark:text-brand-secondary',
                                    ]"
                                >
                                    {{ item.how }}
                                </td>
                                <td
                                    :class="[
                                        'hidden px-3 py-2 text-sm border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:table-cell',
                                        item.deleted_at
                                            ? 'italic text-gray-400'
                                            : 'text-brand-secondary dark:text-brand-secondary',
                                    ]"
                                >
                                    {{ item.updated_at?.short }}
                                </td>
                                <td
                                    class="relative py-2 pl-3 pr-4 text-sm font-medium text-right border-b border-gray-100 dark:border-brand-secondary/20 whitespace-nowrap sm:pr-6 lg:pr-8"
                                >
                                    <ActionTable
                                        :delete-link="!item.deleted_at"
                                        @delete="() => (laporanBeingDeleted = item)"
                                        :restore-link="
                                            item.deleted_at
                                                ? route('admin.wbs.laporan.restore', {
                                                      laporan: item.id,
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
                :links="laporan?.links"
                :prev="laporan?.prev"
                :next="laporan?.next"
                :only="['laporan', 'filters', 'inertiajs']"
            />
        </div>
        <jet-confirmation-modal
            :show="laporanBeingDeleted !== null"
            @close="laporanBeingDeleted = null"
        >
            <template #title>Delete laporan {{ laporanBeingDeleted?.title }}</template>

            <template #content>Are you sure you would like to delete this laporan?</template>

            <template #footer>
                <ButtonWhite @click="laporanBeingDeleted = null">Cancel</ButtonWhite>

                <ButtonDanger
                    class="ml-3"
                    @click="deletelaporan"
                    :class="{ 'opacity-25': deletelaporanForm.processing }"
                    :disabled="deletelaporanForm.processing"
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
    laporan: Object,
    filters: [Array, Object],
    params: [Array, Object],
});

const dateFormat = (date) => dayjs(date).format('DD MMMM YYYY');
const openImportlaporan = ref(false);

const laporanBeingDeleted = ref(null);
const deletelaporanForm = useForm();

const deletelaporan = () =>
    deletelaporanForm.delete(
        route('admin.wbs.laporan.destroy', {
            laporan: laporanBeingDeleted.value?.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (laporanBeingDeleted.value = null),
        }
    );
</script>
