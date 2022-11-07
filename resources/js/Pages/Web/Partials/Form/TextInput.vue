<template>
    <div :class="[inline ? 'field-wrapper-inline' : 'field-wrapper']">
        <slot name="label" :label="label" :id="id">
            <label v-if="label" :for="id" class="form-label">{{ label }}</label>
        </slot>
        <div class="form-wrapper">
            <div class="relative">
                <number
                    :id="id"
                    v-if="type === 'number'"
                    v-model="proxyValue"
                    :class="[...compiledClass]"
                    :placeholder="placeholder || label"
                    :readonly="readonly"
                    :disabled="disabled"
                    ref="input"
                    v-bind="$attrs"
                />
                <input
                    :id="id"
                    v-else
                    v-model="proxyValue"
                    :class="compiledClass"
                    :type="type || 'text'"
                    :placeholder="placeholder || label"
                    :readonly="readonly"
                    :disabled="disabled"
                    ref="input"
                    v-bind="$attrs"
                />

                <slot name="prepend" />
                <div
                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none empty:hidden"
                >
                    <slot />
                    <svg
                        v-if="error"
                        class="w-5 h-5 text-red-500"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
            </div>
            <div v-show="error" class="mt-1 form-error">{{ error }}</div>
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
import { computed, ref } from 'vue';
import { number } from '@coders-tm/vue-number-format';

const id = nanoid();

const emit = defineEmits(['update:modelValue', 'keyup']);

const input = ref(null);

const props = defineProps({
    name: String,
    placeholder: String,
    label: String,
    type: String,
    readonly: Boolean,
    disabled: Boolean,
    modelValue: [String, Number],
    value: [String, Number],
    error: String,
    help: String,
    inputClass: String,
    inline: Boolean,
});

const proxyValue = computed({
    get: () => props.modelValue || props.value,
    set: (value) => emit('update:modelValue', value),
});

const compiledClass = computed(() => {
    return [
        'w-full px-4 py-2 border focus:ring-0 focus:outline-none',
        props.error
            ? 'text-red-800 dark:text-red-500 placeholder-red-400 border-red-500 focus:ring-red-500 focus:border-red-500 disabled:text-red-800/20'
            : 'border-brand-gray focus:border-brand-primary placeholder-[#D2D6DC]',
        props.disabled || props.readonly ? 'select-none bg-opacity-80' : '',
        props.disabled ? 'cursor-not-allowed' : '',
        props.inputClass,
    ];
});

defineExpose({ focus: () => input.value.focus() });
</script>
