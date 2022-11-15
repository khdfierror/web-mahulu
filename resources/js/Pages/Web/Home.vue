<script setup>
import WebLayout from '@/Layouts/WebLayout.vue';
import Hero from './Partials/Hero.vue';
import Info from './Partials/Info.vue';
import Berita from './Partials/Berita.vue';
import Warta from './Partials/Warta.vue';
import Jendela from './Partials/Jendela.vue';
import Peta from './Partials/Peta.vue';
import Dokumen from './Partials/Dokumen.vue';
import Footer from './Partials/Footer.vue';
import RunningText from './Partials/RunningText.vue';
import Administrasi from './Partials/Administrasi.vue';
import Aplikasi from './Partials/Aplikasi.vue';
import Informasi from './Partials/Informasi.vue';

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
            <Info :info="info" />
            <Berita class="my-10" />
            <!-- <Warta v-if="warta?.length" :warta="warta" />
            <Jendela v-if="jendela?.length" :jendela="jendela" class="relative z-10" /> -->
            <!-- <Peta :map_options="map_options" :map_markers="map_markers" /> -->
            <Dokumen class="my-10" />
            <Administrasi class="my-10"/>
            <Aplikasi class="my-10"/>
            <Informasi class="my-10"/>
            <Footer class="relative z-10" />
        </div>
    </WebLayout>
</template>
