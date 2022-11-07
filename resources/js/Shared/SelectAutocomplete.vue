<template>
    <div>
        <label v-if="label" class="form-label" :for="id">{{ label }}</label>
        <Listbox as="div">
            <div class="relative w-full" ref="container">
                <button
                    :title="selected"
                    @click="(open = !open), focusInput()"
                    type="button"
                    :class="[
                        'form-select block w-full border border-brand-secondary/20 rounded-md bg-brand-secondary/[0.03] dark:bg-brand-secondary/20',
                        'focus:outline-none focus:ring-1',
                        error
                            ? [
                                  modelValue ? 'text-red-800' : 'text-red-400',
                                  'border-red-500 focus:ring-red-500 focus:border-red-500 disabled:text-red-800/20',
                              ].join(' ')
                            : ['focus:ring-brand-secondary focus:border-brand-secondary'].join(' '),
                        'sm:text-sm',
                    ]"
                >
                    <div class="flex flex-wrap -m-1">
                        <span
                            v-for="(item, index) in selected"
                            :class="[
                                'inline-flex rounded-full items-center py-0.5 pl-2.5 pr-1 m-0.5',
                                'text-sm',
                                'disabled:text-brand-dark/20',
                                modelValue ? 'text-brand-dark' : 'text-brand-secondary',
                                'dark:disabled:text-white/40',
                                'bg-brand-dark/10 dark:bg-brand-secondary',
                            ]"
                        >
                            {{ item }}
                            <button
                                @click="remove(index)"
                                type="button"
                                :class="[
                                    'flex-shrink-0 ml-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center',
                                    'text-brand-dark/60',
                                    'hover:bg-brand-dark/20 hover:text-brand-dark',
                                    'focus:outline-none focus:bg-brand-dark focus:text-white',
                                ]"
                            >
                                <span class="sr-only">Remove {{ item }} option</span>
                                <svg
                                    class="w-2 h-2"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 8 8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-width="1.5"
                                        d="M1 1l6 6m0-6L1 7"
                                    />
                                </svg>
                            </button>
                        </span>
                        <input
                            ref="searchInput"
                            :placeholder="
                                selected?.length ? '' : placeholder || `Select ${label || ''}`
                            "
                            v-model="search"
                            @keyup.enter.prevent="input"
                            type="text"
                            :class="[
                                'placeholder-brand-secondary',
                                'inline-flex flex-1 w-full bg-transparent border-none p-0 m-1 text-sm',
                                'focus:outline-none focus:border-none focus:ring-0',
                            ]"
                        />
                    </div>
                </button>

                <transition
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-show="open && filtered?.length"
                        :class="[
                            'absolute z-10 w-full mt-1 overflow-hidden text-base rounded-md shadow-lg sm:text-sm',
                            'bg-white dark:bg-transparent',
                            ' focus:outline-none',
                            'border border-brand-secondary/20',
                        ]"
                    >
                        <ul
                            class="-my-px overflow-auto max-h-60 bg-brand-secondary/[0.03] dark:bg-brand-secondary/20 dark:backdrop-blur-sm"
                        >
                            <ListboxOption
                                as="template"
                                v-for="(item, index) in filtered"
                                :key="index"
                                :value="item"
                                v-slot="{ active, selected }"
                            >
                                <li
                                    :class="[
                                        selected
                                            ? 'bg-brand-red/10 border-brand-red'
                                            : 'hover:bg-gray-50 dark:hover:bg-brand-secondary/30',
                                        'pl-4 py-2',
                                        'cursor-default select-none border-t border-b border-transparent relative pr-4 hover:border-gray-200 dark:hover:border-brand-secondary/30',
                                    ]"
                                    @click="select(item)"
                                >
                                    <slot name="item" :value="item">
                                        <span :class="['block truncate']">
                                            {{ item }}
                                        </span>
                                    </slot>
                                </li>
                            </ListboxOption>
                            <template v-if="!filtered?.length && false">
                                <ListboxOption v-if="search" v-slot="{ active, selected }">
                                    <li
                                        :class="[
                                            selected
                                                ? 'bg-gray-100 border-brand-dark'
                                                : 'hover:bg-gray-50 dark:hover:bg-brand-secondary/10',
                                            'px-3 py-2',
                                            'cursor-default select-none border-t border-b border-transparent relative hover:border-gray-200 dark:hover:border-brand-secondary/30',
                                        ]"
                                        @click="select(search)"
                                    >
                                        <span :class="['block truncate text-brand-dark']">
                                            Tambah
                                            <span class="font-semibold">
                                                {{ search }}
                                            </span>
                                        </span>
                                    </li>
                                </ListboxOption>
                                <ListboxOption v-else v-slot="{ active, selected }">
                                    <li
                                        :class="[
                                            'px-3 py-2',
                                            'cursor-default select-none border-t border-b border-transparent relative hover:border-gray-200',
                                        ]"
                                    >
                                        <span
                                            :class="['block truncate text-brand-secondary text-sm']"
                                        >
                                            Ketik Untuk Menambah
                                        </span>
                                    </li>
                                </ListboxOption>
                            </template>
                        </ul>
                    </div>
                </transition>
            </div>
        </Listbox>
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { onClickOutside, debouncedWatch } from '@vueuse/core';
import { Listbox, ListboxOption } from '@headlessui/vue';
import { nanoid } from 'nanoid';

const emit = defineEmits(['update:modelValue', 'close', 'open']);

const id = nanoid();
const search = ref(null);
const searchInput = ref(null);
const open = ref(false);
const container = ref(null);

debouncedWatch(
    () => open.value,
    (value) => {
        if (value) emit('open');
        else emit('close');
    },
    {
        debounce: 200,
    }
);

const props = defineProps({
    label: String,
    error: String,
    placeholder: String,
    options: Array,
    forceUpperCase: Boolean,
    modelValue: [Array],
});

const selected = ref(props.modelValue || []);
const filtered = computed(() =>
    props.options
        ?.filter((i) => selected.value?.indexOf(i) === -1)
        ?.filter((i) => {
            if (search.value) {
                return i?.toLowerCase().indexOf(search.value?.toLowerCase()) !== -1;
            } else {
                return i;
            }
        })
);

const focusInput = () => searchInput.value.focus();

const select = (item) => {
    const value = props.forceUpperCase ? item?.toUpperCase() : item;

    if (selected.value.indexOf(value) === -1) selected.value.push(value);
    search.value = null;

    focusInput();
};

const input = ($event) => {
    if (filtered.value?.length) {
        select(filtered.value[0]);
    } else {
        select(search.value);
    }
};

watch(
    () => selected.value,
    (value) => emit('update:modelValue', value),
    {
        deep: true,
    }
);

const remove = (index) => {
    selected.value.splice(index, 1);
    focusInput();
};

onClickOutside(container, (event) => {
    if (open.value) open.value = false;
});

onMounted(() => {});
</script>
