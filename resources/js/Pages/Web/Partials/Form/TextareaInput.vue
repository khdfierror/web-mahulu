<template>
    <div :class="[inline ? 'field-wrapper-inline' : 'field-wrapper']">
        <slot name="label" :label="label" :id="id">
            <label v-if="label" :for="id" class="form-label">{{ label }}</label>
        </slot>
        <div class="form-wrapper">
            <div class="relative">
                <textarea
                    :id="id"
                    ref="input"
                    :class="[
                        'w-full px-4 py-2 border focus:ring-0 focus:outline-none',
                        'disabled:select-none',
                        error
                            ? 'text-red-800 placeholder-red-400 border-red-500 focus:ring-red-500 focus:border-red-500 disabled:text-red-800/20'
                            : 'border-brand-gray focus:border-brand-primary placeholder-[#D2D6DC]',
                        disabled ? 'cursor-not-allowed resize-none' : '',
                        inputClass,
                    ]"
                    :type="type || 'text'"
                    :placeholder="placeholder || label"
                    :value="modelValue"
                    :rows="rows"
                    :disabled="disabled"
                    :style="`min-height: ${minHeight}px`"
                    @input="$emit('update:modelValue', $event.target.value)"
                    @keyup="detectHeight"
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
            <div v-show="error" class="mt-1 form-error">
                {{ error }}
            </div>
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

import { ref, onMounted } from 'vue';

const id = `textarea-input-${nanoid()}`;

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    name: String,
    placeholder: String,
    label: String,
    type: String,
    modelValue: [String, Number],
    error: String,
    help: String,
    inline: Boolean,
    disabled: Boolean,
    rows: {
        type: [String, Number],
        default: 5,
    },
    inputClass: String,
    autoGrow: Boolean,
});

const input = ref(null);
const minHeight = ref(props.rows * 20);

const detectHeight = () => {
    if (!props.autoGrow) return;

    const numberOfLineBreaks = (props.modelValue?.match(/\n/g) || []).length;
    // min-height + lines x line-height + padding + border
    return (minHeight.value = 20 + numberOfLineBreaks * 20 + 40 + 2);
};

onMounted(() => {
    detectHeight();
});
</script>
