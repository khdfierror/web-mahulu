<template>
    <div :class="[inline ? 'field-wrapper-inline' : 'field-wrapper']">
        <slot name="label" :label="label" :id="id">
            <label v-if="label" class="form-label" :for="id">{{ label }}</label>
        </slot>
        <div class="form-wrapper">
            <Menu v-slot="{ open }" as="div" class="relative w-full mr-4 text-left">
                <div class="flex">
                    <MenuButton
                        @click="handleMenuButton(open)"
                        :id="id"
                        :class="[
                            'pr-8 flex flex-wrap text-left w-full border px-2 py-1 rounded-md bg-brand-secondary/[0.03] dark:bg-brand-secondary/20',
                            'focus:outline-none focus:ring-1',
                            error
                                ? [
                                      modelValue ? 'text-red-800' : 'text-red-400',
                                      'border-red-500 focus:ring-red-500 focus:border-red-500',
                                  ].join(' ')
                                : 'border-brand-secondary/20 focus:ring-brand-secondary focus:border-brand-secondary text-brand-dark',
                            'sm:text-sm',
                            size,
                        ]"
                    >
                        <template v-for="item in options">
                            <span
                                v-if="proxyValue.indexOf(item.id) !== -1"
                                class="flex items-center max-w-xs py-0.5 select-none px-1 ring-1 ring-gray-200 m-1 text-xs truncate bg-gray-100 rounded-md"
                                >{{ item?.label || '&#8203;' }}</span
                            >
                        </template>
                        <div
                            v-if="!proxyValue?.length"
                            :class="['m-1', error ? 'text-red-400' : 'text-brand-secondary']"
                        >
                            {{ placeholder || `Select ${label}` }}
                        </div>

                        <icon-heroicons-outline-chevron-down
                            class="absolute right-2.5 top-2.5 w-4 h-4 text-brand-secondary"
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
                    <MenuItems class="w-full component-dropdown">
                        <div
                            v-if="filterable"
                            class="mb-2 -mx-1 -mt-1 border-b border-brand-secondary/20"
                        >
                            <input
                                v-model="filterModel"
                                ref="filterInput"
                                :class="[
                                    'w-full bg-transparent px-4 py-2 text-sm placeholder-brand-secondary',
                                    'focus:outline-none',
                                ]"
                                placeholder="Search..."
                            />
                        </div>
                        <template v-for="item in filteredOptions">
                            <button
                                type="button"
                                @click="toggleSelect(item)"
                                class="relative pr-3 component-dropdown-item"
                            >
                                {{ item.label }}
                                <icon-tabler-check
                                    v-if="proxyValue.indexOf(item.id) !== -1"
                                    class="absolute right-0 w-5 h-5 mr-3"
                                />
                            </button>
                        </template>
                        <div
                            v-if="!filteredOptions?.length"
                            class="px-3 py-1 text-sm text-center text-brand-secondary dark:text-white/80"
                        >
                            No Data
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
            <div v-if="error" class="mt-1 form-error">{{ error }}</div>
            <slot name="help">
                <div class="mt-1 text-xs text-brand-secondary" v-if="help">
                    {{ help }}
                </div>
            </slot>
        </div>
    </div>
</template>

<script setup>
import { nanoid } from 'nanoid';
import { ref, computed, onMounted, watch } from 'vue';
import { watchDebounced, useFocus } from '@vueuse/core';

import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';

const input = ref(null);
const emit = defineEmits(['update:modelValue']);
const props = defineProps({
    id: {
        type: String,
        default() {
            return `select-input-${nanoid()}`;
        },
    },
    size: {
        type: String,
        default: 'lg',
    },
    help: String,
    options: [Array, Object],
    placeholder: String,
    modelValue: [Array],
    disabled: {
        type: Boolean,
        required: false,
    },
    filterable: Boolean,
    label: String,
    error: String,
    inline: Boolean,
    initValue: {
        type: Boolean,
        default: true,
    },
});

const proxyValue = ref(props.modelValue || []);

const filterInput = ref(null);
const filterModel = ref(null);
const filteredOptions = ref(props.options || []);

watch(
    () => props.options,
    (options) => {
        filteredOptions.value = options;
    }
);

const { focused: filterInputFocus } = useFocus(filterInput);

watchDebounced(
    () => filterModel.value,
    (value) =>
        (filteredOptions.value = props.options?.filter((item, index) => {
            return item?.label?.toLowerCase().indexOf(value?.toLowerCase()) !== -1;
        })),
    { debounce: 200 }
);

const handleMenuButton = (open) => {
    if (!open) setTimeout(() => (filterInputFocus.value = true), 200);
    else setTimeout(() => (filterInputFocus.value = false), 200);
};

const toggleSelect = (item) => {
    const index = proxyValue.value.indexOf(item.id);
    if (index === -1) {
        proxyValue.value.push(item.id);
    } else {
        proxyValue.value.splice(index, 1);
    }

    emit('update:modelValue', proxyValue.value);
};

onMounted(() => {});
</script>
