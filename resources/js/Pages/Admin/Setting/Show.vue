<template>
    <AppLayout menu="setting" title="Settings">
        <template #header>
            <div>
                <div class="flex items-center">
                    <div class="flex-1">
                        <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                            Settings
                        </h2>
                    </div>
                    <div class="inline-flex items-center"></div>
                </div>
            </div>
        </template>
        <Site
            v-if="features.indexOf('site') !== -1"
            :params="settings?.['site']"
            :options="options?.site"
            class="mt-6"
        />
        <Service :params="settings?.['service']" :options="options?.service" class="mt-6" />
        <About :params="settings?.['about']" :options="options?.about" class="mt-6" />
        <Greeting
            v-if="features.indexOf('greeting') !== -1"
            :params="settings?.['greeting']"
            :options="options?.greeting"
            class="mt-6"
        />
        <SocialMedia
            v-if="features.indexOf('social-media') !== -1"
            :params="settings?.['social-media']"
            :options="options?.['social-media']"
            class="mt-6"
        />
        <Contact
            v-if="features.indexOf('contact') !== -1"
            :params="settings?.['contact']"
            :options="options?.contact"
            class="mt-6"
        />
    </AppLayout>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SocialMedia from './Partials/SocialMedia.vue';
import Greeting from './Partials/Greeting.vue';
import Contact from './Partials/Contact.vue';
import Site from './Partials/Site.vue';
import Service from './Partials/Service.vue';
import About from './Partials/About.vue';

defineProps({
    settings: Object,
    options: Object,
    features: Array,
});
</script>
