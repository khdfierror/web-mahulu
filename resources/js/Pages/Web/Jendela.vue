<script setup>
import WebLayout from '@/Layouts/WebLayout.vue';
import Back from '~icons/ri/arrow-left-fill';

defineProps({
    post: Object,
});
</script>

<template>
    <WebLayout :title="post?.slug">
        <div class="bg-brand-light dark:bg-brand-dark px-4 py-16 sm:px-6 lg:px-8 relative">
            <div class="flex flex-col items-center justify-center max-w-6xl mx-auto relative">
                <div class="flex justify-between gap-5 w-full">
                    <h1 class="text-lg font-semibold capitalize">
                        <Link
                            :href="route('home')"
                            class="flex group items-center align-middle gap-1 hover:text-brand-primary dark:hover:text-brand-light"
                        >
                            <Back class="w-5 h-5 fill-current" />
                            Kembali
                        </Link>
                    </h1>
                </div>
                <h1 class="text-4xl font-semibold">{{ post?.title }}</h1>
                <img
                    :src="post?.image"
                    :alt="post?.slug"
                    class="relative z-10 object-cover w-full h-full mt-7"
                />
                <span class="mt-7 prose max-w-none" v-html="post?.content"></span>
            </div>
        </div>
    </WebLayout>
</template>
