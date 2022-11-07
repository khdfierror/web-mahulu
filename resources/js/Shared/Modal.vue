<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    wrapperClass: {
        type: [String, Array, Object],
        default:
            'px-4 pt-5 pb-4 text-left mx-auto bg-white dark:bg-black/80 dark:backdrop-blur rounded-lg shadow-xl sm:p-6',
    },
});

const emit = defineEmits(['close', 'update:modelValue']);

watch(
    () => props.modelValue,
    () => {
        if (props.modelValue) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

const show = computed(() => props.modelValue);

const close = () => {
    if (props.closeable) {
        emit('close');
        emit('update:modelValue', false);
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.modelValue) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '3xl': 'sm:max-w-3xl',
        '4xl': 'sm:max-w-4xl',
        '5xl': 'sm:max-w-5xl',
        '6xl': 'sm:max-w-6xl',
        '7xl': 'sm:max-w-7xl',
        full: 'sm:max-w-full',
    }[props.maxWidth];
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div
                v-show="show"
                class="fixed inset-0 z-50 px-4 py-6 overflow-y-auto text-center sm:p-0"
                scroll-region
            >
                <transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-show="show"
                        class="fixed inset-0 transition-all transform"
                        @click="close"
                    >
                        <slot name="backdrop">
                            <div class="absolute inset-0 bg-gray-500 opacity-75" />
                        </slot>
                    </div>
                </transition>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"
                    >&#8203;</span
                >

                <transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                    enter-to-class="translate-y-0 opacity-100 sm:scale-100"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="translate-y-0 opacity-100 sm:scale-100"
                    leave-to-class="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        :class="[
                            'inline-block w-full align-bottom transition-all transform sm:my-8 sm:align-middle sm:w-full',
                            maxWidthClass,
                        ]"
                    >
                        <slot>
                            <div :class="wrapperClass">
                                <div class="absolute -top-3 -right-3" v-if="closeable">
                                    <button
                                        type="button"
                                        :class="[
                                            'p-1 rounded-full shadow-lg ring-1 ring-brand-dark/10 text-brand-dark bg-white/50 backdrop-blur-sm',
                                            'hover:text-gray-700',
                                            'focus:text-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800',
                                        ]"
                                        @click="close"
                                    >
                                        <span class="sr-only">Close</span>
                                        <XMarkIcon class="w-6 h-6" aria-hidden="true" />
                                    </button>
                                </div>
                                <slot name="body">
                                    <div class="sm:flex sm:items-start">
                                        <template v-if="$slots.icon">
                                            <slot name="icon" />
                                        </template>
                                        <div
                                            :class="[
                                                'text-center sm:text-left',
                                                $slots.icon ? 'mt-3 sm:mt-0 sm:ml-4' : 'flex-1',
                                            ]"
                                        >
                                            <slot name="header">
                                                <h3
                                                    v-if="$slots.title"
                                                    class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
                                                >
                                                    <slot name="title" />
                                                </h3>
                                            </slot>
                                            <div class="w-full mt-2" v-if="$slots.content">
                                                <slot name="content" />
                                            </div>
                                        </div>
                                    </div>
                                </slot>
                                <div
                                    v-if="$slots.footer"
                                    class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse"
                                >
                                    <slot name="footer" />
                                </div>
                            </div>
                        </slot>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
