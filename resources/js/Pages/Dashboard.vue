<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({});
</script>

<template>
    <AppLayout title="Dashboard" menu="dashboard">
        <template #header>
            <div class="flex items-center px-4 md:px-0">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">Dashboard</h2>
                </div>
            </div>
        </template>

        <div class="min-h-[50vh]">
            <div class="flex flex-wrap mt-8 space-y-4 md:space-y-0 md:space-x-8">
                <div
                    class="overflow-hidden w-full md:flex-1 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
                >
                    <div class="p-4">Welcome {{ $page.props.user?.name }}</div>
                </div>
                <div
                    class="overflow-hidden w-full md:flex-1 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
                >
                    <div class="p-4">BPCB</div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
