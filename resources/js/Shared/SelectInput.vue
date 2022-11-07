<template>
    <div :class="[inline ? 'field-wrapper-inline' : 'field-wrapper']">
        <slot name="label" :label="label" :id="id">
            <label v-if="label" class="form-label" :for="id">{{ label }}</label>
        </slot>
        <div class="relative form-wrapper">
            <select
                ref="input"
                :id="id"
                :class="[
                    'form-select block w-full border border-brand-secondary/20 rounded-md bg-brand-secondary/[0.03] dark:bg-brand-secondary/20',
                    'focus:outline-none focus:ring-1',
                    computedClass,
                    error
                        ? [
                              modelValue ? 'text-red-800' : 'text-red-400',
                              'border-red-500 focus:ring-red-500 focus:border-red-500 disabled:text-red-800/20',
                          ].join(' ')
                        : [
                              'focus:ring-brand-secondary focus:border-brand-secondary',
                              'disabled:text-brand-dark/20',
                              modelValue
                                  ? 'text-brand-dark dark:text-white/80'
                                  : 'text-brand-secondary',
                              'dark:disabled:text-white/40',
                          ].join(' '),
                    'sm:text-sm',
                    size,
                ]"
                @change="$emit('update:modelValue', $event.target.value)"
                :disabled="disabled"
            >
                <option
                    value
                    :disabled="!allowEmpty"
                    :selected="!modelValue"
                    class="dark:text-brand-secondary"
                >
                    {{ placeholder || `Select ${label || ''}` }}
                </option>
                <option
                    v-for="item in options"
                    :key="item.id"
                    :value="item.id"
                    :selected="modelValue == item.id"
                >
                    {{ item.label }}
                </option>
            </select>
            <div v-if="error" class="mt-1 empty:hidden form-error">{{ error }}</div>
            <slot name="help">
                <div class="mt-1 text-xs empty:hidden text-brand-secondary" v-if="help">
                    {{ help }}
                </div>
            </slot>
        </div>
    </div>
</template>

<script setup>
import { nanoid } from 'nanoid';
import { computed, ref, onMounted } from 'vue';

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
        default: 'md',
    },
    options: [Array, Object],
    placeholder: String,
    modelValue: [String, Number],
    disabled: {
        type: Boolean,
        required: false,
    },
    label: String,
    help: String,
    error: String,
    inline: Boolean,
    allowEmpty: Boolean,
    initValue: {
        type: Boolean,
        default: true,
    },
});

const computedClass = computed(() => {
    return [
        {
            lg: 'px-4 py-3',
            md: 'px-3 py-2',
        }[props.size],
    ];
});

onMounted(() => {
    const firstId = props.options?.[0]?.id?.toString();
    // if (!props.modelValue && firstId && props.initValue) {
    // 	emit("update:modelValue", firstId);
    // }
});
</script>
