<script setup>
import Logo from '@/Shared/Logo.vue';

import SendMessage from './SendMessage.vue';

const visitor = computed(() => usePage().props.value?.visitor);
const footer = computed(() => usePage().props.value?.settings?.site.footer);
const contact = computed(() => usePage().props.value?.settings?.contact);

const socialMedia = computed(() => usePage().props.value?.settings?.['social-media']);
</script>
<template>
    <div class="text-white bg-brand-primary">
        <div class="px-12 sm:px-14 lg:px-16">
            <div class="grid max-w-6xl py-10 mx-auto md:grid-cols-2 md:gap-8">
                <div class="flex flex-col justify-between py-10">
                    <div class="py-6">
                        <Logo class="h-14" />
                    </div>
                    <div class="py-6" v-if="contact">
                        <ul class="space-y-3">
                            <li class="flex" v-if="contact?.email">
                                <IconRiMailLine class="w-5 h-auto mr-3 text-brand-gray" />
                                <a :href="`mailto:${contact?.email}`" class="text-brand-light">{{
                                    contact?.email
                                }}</a>
                            </li>
                            <li class="flex" v-if="contact?.phone">
                                <IconRiPhoneLine class="w-5 h-auto mr-3 text-brand-gray" />
                                <a :href="`tel:${contact?.phone}`" class="text-brand-light">{{
                                    contact?.phone
                                }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="py-6">
                        <div class="font-bold">Informasi Pengunjung</div>
                        <div class="flex mt-8 space-x-8">
                            <div class="text-center">
                                <div class="text-3xl font-semibold">{{ visitor?.day || '0' }}</div>
                                <div class="text-brand-gray">Hari ini</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-semibold">{{ visitor?.week || '0' }}</div>
                                <div class="text-brand-gray">Minggu ini</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-semibold">
                                    {{ visitor?.month || '0' }}
                                </div>
                                <div class="text-brand-gray">Bulan ini</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-semibold">{{ visitor?.year || '0' }}</div>
                                <div class="text-brand-gray">Tahun ini</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-10">
                    <div class="relative max-w-md ml-auto">
                        <div class="relative z-10 p-6 bg-white text-brand-dark">
                            <SendMessage />
                        </div>
                        <div
                            class="absolute z-0 w-full h-full border border-brand-gray -bottom-6 -right-6"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-t-[#206FA1] px-12 sm:px-14 lg:px-16">
            <div class="flex flex-wrap items-center max-w-6xl py-10 mx-auto">
                <div>
                    {{ footer || '© 2022 Balai Pelestarian Cagar Budaya Kalimantan.' }}
                </div>
                <div class="lg:ml-auto" v-if="socialMedia">
                    <ul class="flex space-x-5">
                        <li v-if="socialMedia?.facebook">
                            <a
                                :href="socialMedia?.facebook?.link"
                                target="_blank"
                                class="transition-all text-brand-gray hover:text-white"
                            >
                                <IconRiFacebookCircleFill class="w-5 h-5" />
                            </a>
                        </li>
                        <li v-if="socialMedia?.instagram">
                            <a
                                :href="socialMedia?.instagram?.link"
                                target="_blank"
                                class="transition-all text-brand-gray hover:text-white"
                            >
                                <IconRiInstagramFill class="w-5 h-5" />
                            </a>
                        </li>
                        <li v-if="socialMedia?.twitter">
                            <a
                                :href="socialMedia?.twitter?.link"
                                target="_blank"
                                class="transition-all text-brand-gray hover:text-white"
                            >
                                <IconRiTwitterFill class="w-5 h-5" />
                            </a>
                        </li>
                        <li v-if="socialMedia?.youtube">
                            <a
                                :href="socialMedia?.youtube?.link"
                                target="_blank"
                                class="transition-all text-brand-gray hover:text-white"
                            >
                                <IconRiYoutubeFill class="w-5 h-5" />
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
