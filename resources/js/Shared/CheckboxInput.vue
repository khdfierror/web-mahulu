<template>
    <div class="relative flex flex-wrap items-start">
        <div class="flex items-center h-5">
            <input
                :id="id"
                type="checkbox"
                v-model="proxyChecked"
                :value="value"
                :class="[
                    'w-4 h-4 transition duration-150 ease-in-out rounded dark:bg-brand-dark dark:ring-offset-brand-dark',
                    error
                        ? 'focus:ring-red-500 border-red-500 text-red-600 ring-1 ring-red-500'
                        : 'text-brand-primary border-brand-secondary/30 focus:ring-brand-primary/90',
                ]"
            />
        </div>
        <div class="flex-1 ml-3 text-sm">
            <label :for="id" v-if="$slots.default">
                <slot />
            </label>
            <label v-else :for="id" class="text-brand-dark/80 dark:text-white/80">{{
                label
            }}</label>
            <p v-if="description" class="text-brand-secondary empty:hidden">{{ description }}</p>
            <div v-show="error" class="mt-1 text-sm text-red-600">{{ error }}</div>
        </div>
        <slot name="append" />
    </div>
</template>

<script setup>
import { nanoid } from 'nanoid';
import { computed } from 'vue';

const id = nanoid();

const emit = defineEmits(['update:checked']);

const props = defineProps({
    label: String,
    description: String,
    error: String,
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        type: String,
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>
