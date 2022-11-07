<template>
    <div>
        <label v-if="label" class="form-label" :for="id">{{ label }}</label>
        <DatePicker v-model="model" color="orange" :is-dark="isDark">
            <template v-slot="{ inputValue, togglePopover }">
                <div class="relative w-full">
                    <div
                        :class="[
                            'absolute inset-y-0 right-0 flex items-center pointer-events-none',
                            error ? 'pr-10 text-red-500' : 'text-brand-secondary pr-3',
                        ]"
                    >
                        <icon-heroicons-outline-calendar :class="['w-5 h-5']" />
                    </div>
                    <input
                        ref="input"
                        type="text"
                        :id="id"
                        :placeholder="placeholder || format"
                        :class="[
                            'block w-full border pl-3 pr-10 py-2 rounded-md bg-brand-secondary/[0.03] dark:bg-brand-secondary/20',
                            'focus:outline-none focus:ring-1',
                            'disabled:select-none',
                            error
                                ? 'text-red-800 placeholder-red-400 border-red-500 focus:ring-red-500 focus:border-red-500 disabled:text-red-800/20'
                                : 'border-brand-secondary/20 placeholder-brand-secondary focus:ring-brand-secondary focus:border-brand-secondary text-brand-dark disabled:text-brand-dark/20 dark:text-white/80 dark:disabled:text-white/40',
                            'sm:text-sm',
                            disabled ? 'cursor-not-allowed' : '',
                            inputClass,
                        ]"
                        :value="display"
                        @focus="togglePopover"
                        @blur="togglePopover"
                    />
                    <div
                        v-show="error"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
                    >
                        <svg
                            class="w-5 h-5 text-red-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </div>
                </div>
            </template>
        </DatePicker>
        <div v-if="error" class="mt-1 form-error">{{ error }}</div>
    </div>
</template>

<style>
.vc-container.vc-is-dark {
    @apply bg-brand-dark/80 backdrop-blur-sm border-brand-secondary/20;
}
</style>

<script setup>
import { nanoid } from 'nanoid';
import { computed, ref, watch, onMounted } from 'vue';
import { DatePicker } from 'v-calendar';
import dayjs from 'dayjs';
import { useDark } from '@vueuse/core';

const isDark = useDark();

const emit = defineEmits(['update:modelValue']);
const props = defineProps({
    id: {
        type: String,
        default() {
            return `date-input-${nanoid()}`;
        },
    },
    format: {
        type: String,
        default: 'DD MMMM YYYY',
    },
    placeholder: String,
    modelValue: [String, Number, Date],
    disabled: {
        type: Boolean,
        required: false,
    },
    label: String,
    inputClass: String,
    now: {
        type: Boolean,
        default: false,
    },
    error: String,
});

const input = ref(null);
const model = ref(props.modelValue);

const display = computed(() => {
    const result = dayjs(model.value).format(props.format);
    if (result === 'Invalid Date') {
        return '';
    } else {
        return result;
    }
});

watch(
    () => model.value,
    (value) => emit('update:modelValue', value)
);

onMounted(() => {
    if (props.now && !props.modelValue) model.value = new Date();
});
</script>
