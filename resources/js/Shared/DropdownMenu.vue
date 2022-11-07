<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: Array,
        default: () => ['py-4', 'bg-brand-primary'],
    },
});

const open = ref(true);
const trigger = ref(null);
const triggerWidth = ref(null);

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        48: 'w-48',
        full: 'w-[52rem] transform -translate-x-1/2',
    }[props.width.toString()];
});

const arrowStyle = computed(
    () => `width: ${triggerWidth.value || 100}px; ${props.width === 'full' ? '' : 'right:0;'}`
);

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'origin-top-left left-0';
    }

    if (props.align === 'right') {
        return 'origin-top-right right-0';
    }

    return 'origin-top';
});

onMounted(() => {
    triggerWidth.value = trigger.value?.offsetWidth;
    console.log(trigger.value?.offsetLeft);
});
</script>

<template>
    <div class="relative">
        <div @click="open = !open" ref="trigger">
            <slot name="trigger" />
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false" />

        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-show="open"
                class="absolute z-50 mt-2 text-white bg-brand-primary"
                :class="[widthClass, alignmentClasses]"
                style="display: none"
                @click="open = false"
            >
                <div class="absolute inset-x-0 flex items-center -top-1">
                    <div v-if="width === 'full'" :style="`width: ${triggerWidth}px`"></div>
                    <div class="flex-1">
                        <div class="inset-x-0 mx-auto arrow-up" />
                    </div>
                </div>
                <div class="ring-1 ring-black ring-opacity-5" :class="contentClasses">
                    <slot name="content" />
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.arrow-up {
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    /* left arrow slant */
    border-right: 5px solid transparent;
    /* right arrow slant */
    border-bottom: 5px solid #ffffff;
    /* bottom, add background color here */
    font-size: 0;
    line-height: 0;
}
</style>
