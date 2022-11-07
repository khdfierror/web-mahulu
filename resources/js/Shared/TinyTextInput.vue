<template>
    <div class="relative">
        <input
            :value="modelValue"
            :class="[
                'ring-none w-full outline-none focus:ring-0 text-xs border-none placeholder:text-brand-secondary bg-brand-secondary/[0.03] dark:bg-brand-secondary/20',
                'px-2 py-1.5 w-full rounded-sm',
                'read-only:cursor-default read-only:opacity-50',
                {
                    left: 'text-left',
                    center: 'text-center',
                    right: 'text-right',
                }[props.align || 'left'],
            ]"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="$attrs.placeholder"
            :readonly="$attrs.readonly"
            :disabled="$attrs.disabled"
            v-bind="$attrs"
        />

        <slot name="prepend"></slot>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const emit = defineEmits(['update:modelValue']);
const props = defineProps({
    modelValue: [String, Number],
    type: [String],
    align: String,
});
</script>
