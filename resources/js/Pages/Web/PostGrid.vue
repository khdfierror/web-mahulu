<script setup>
import WebLayout from '@/Layouts/WebLayout.vue';
import Back from '~icons/ri/arrow-left-fill';

defineProps({
    posts: Object,
    params: Object,
});
</script>

<template>
    <WebLayout :title="params.category">
        <div class="bg-brand-light dark:bg-brand-dark px-4 py-16 sm:px-6 lg:px-8 relative">
            <div class="flex flex-col items-center justify-center max-w-6xl mx-auto relative">
                <h1 class="text-4xl font-semibold capitalize">{{ params.category }}</h1>
                <div class="flex justify-between gap-5 w-full">
                    <h1 class="text-lg font-semibold capitalize">
                        <Link
                            href="/"
                            class="flex group items-center align-middle gap-1 hover:text-brand-primary dark:hover:text-brand-light"
                        >
                            <Back class="w-5 h-5 fill-current" />
                            Kembali
                        </Link>
                    </h1>
                </div>
                <div class="grid grid-cols-3 gap-8 mt-10">
                    <div
                        :href="route('web.post.show', item.id)"
                        class="flex flex-col overflow-hidden"
                        v-for="(item, index) in posts"
                        :key="index"
                    >
                        <Link
                            :href="route('web.post.show', item.id)"
                            class="aspect-square h-96 w-auto"
                        >
                            <img
                                :src="item?.image"
                                :alt="item.slug"
                                class="relative z-10 object-cover w-full h-full"
                            />
                        </Link>
                        <div class="font-semibold mt-7">{{ item.title }}</div>
                        <span class="mt-3" v-html="item.content"> </span>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
