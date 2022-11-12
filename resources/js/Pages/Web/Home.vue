<script setup>
import WebLayout from '@/Layouts/WebLayout.vue';
import Hero from './Partials/Hero.vue';
import About from './Partials/About.vue';
import Layanan from './Partials/Layanan.vue';
import Warta from './Partials/Warta.vue';
import Jendela from './Partials/Jendela.vue';
import Peta from './Partials/Peta.vue';
import SocialMedia from './Partials/SocialMedia.vue';
import Footer from './Partials/Footer.vue';
import RunningText from './Partials/RunningText.vue';
import Survey from './Partials/Survey.vue';

defineProps({
    settings: Object,
    survey: [Object, Boolean],
    warta: Object,
    jendela: Object,
    facebook: [Object, Array],
    map_options: [Object, Array],
    map_markers: [Object, Array],
    slider: [Object, Array],
    about: [Object, Array],
});
</script>

<template>
    <WebLayout title="Home">
        <div class="bg-brand-light">
            <Hero />
            <!-- <RunningText v-if="tickers" class="relative z-10 -mb-8" /> -->
            <About :about="about" />
            <Layanan class="relative z-10" />
            <!-- <Warta v-if="warta?.length" :warta="warta" />
            <Jendela v-if="jendela?.length" :jendela="jendela" class="relative z-10" /> -->
            <!-- <Peta :map_options="map_options" :map_markers="map_markers" /> -->
            <SocialMedia class="relative z-10" />
            <Survey class="relative z-10"/>
            <Footer class="relative z-10" />
        </div>
    </WebLayout>
</template>
