<template>
    <div
        v-show="open"
        class="absolute inset-0 z-20 flex items-center justify-center w-full h-full transition ease-in-out bg-slate-900/50"
    >
        <svg
            class="w-6 h-6 mr-3 -ml-1 text-white animate-spin"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            ></circle>
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
        </svg>
        <div class="text-sm leading-6 text-white">Mohon tunggu...</div>
    </div>
</template>

<script setup>
defineProps({
    open: Boolean,
});
</script>
