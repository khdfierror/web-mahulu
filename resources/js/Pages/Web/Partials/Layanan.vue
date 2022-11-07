<template>
    <div class="px-12 bg-brand-primary sm:px-14 lg:px-16 py-28">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col items-center justify-center text-brand-light">
                <h1 class="text-4xl font-semibold text-center">Layanan Kami</h1>
                <div class="grid min-w-full grid-cols-4 gap-8 mt-20">
                    <Link
                        :href="services?.perizinan"
                        class="aspect-square flex max-h-[270px] max-w-[270px] bg-white hover:bg-brand-dark hover:text-white text-brand-dark"
                    >
                        <div
                            class="flex flex-col w-full gap-y-6 p-auto items-center justify-center p-[4.5rem]"
                        >
                            <Icon class="flex w-12 h-12" name="layanan" />
                            <h1 class="self-center text-base font-semibold align-top">Perizinan</h1>
                        </div>
                    </Link>
                    <Link
                        :href="services?.odcb"
                        class="aspect-square flex max-h-[270px] max-w-[270px] bg-white hover:bg-brand-dark hover:text-white text-brand-dark"
                    >
                        <div
                            class="flex flex-col w-full gap-y-6 p-auto items-center justify-center p-[4.5rem]"
                        >
                            <Icon class="flex w-12 h-12" name="layanan" />
                            <h1 class="text-base font-semibold text-center align-top">
                                Pelaporan <br />
                                Temuan ODCB
                            </h1>
                        </div>
                    </Link>
                    <Link
                        :href="services?.data_cb"
                        class="aspect-square flex max-h-[270px] max-w-[270px] bg-white hover:bg-brand-dark hover:text-white text-brand-dark"
                    >
                        <div
                            class="flex flex-col w-full gap-y-6 p-auto items-center justify-center p-[4.5rem]"
                        >
                            <Icon class="flex w-12 h-12" name="layanan" />
                            <h1 class="text-base font-semibold text-center align-top">
                                Permohonan <br />
                                Data CB
                            </h1>
                        </div>
                    </Link>
                    <Link
                        :href="services?.kasus_cb"
                        class="aspect-square flex max-h-[270px] max-w-[270px] bg-white hover:bg-brand-dark hover:text-white text-brand-dark"
                    >
                        <div
                            class="flex flex-col w-full gap-y-6 p-auto items-center justify-center p-[4.5rem]"
                        >
                            <Icon class="flex w-12 h-12" name="layanan" />
                            <h1 class="text-base font-semibold text-center align-top">
                                Pelaporan <br />
                                Kasus CB
                            </h1>
                        </div>
                    </Link>
                </div>
                <div class="mt-20 flex flex-row gap-[5.625rem] h-full">
                    <template v-if="services?.sp4n_lapor?.startsWith('https://')">
                        <a :href="services?.sp4n_lapor" class="block" target="_blank">
                            <Icon
                                class="flex w-full h-12 text-brand-gray hover:text-brand-light"
                                name="sp4n"
                            />
                        </a>
                    </template>
                    <template v-else>
                        <Link :href="services?.sp4n_lapor" class="block">
                            <Icon
                                class="flex w-full h-12 text-brand-gray hover:text-brand-light"
                                name="sp4n"
                            />
                        </Link>
                    </template>
                    <Link :href="services?.gratifikasi" class="block">
                        <Icon
                            class="flex w-full h-12 text-brand-gray hover:text-brand-light"
                            name="gratifikasi"
                        />
                    </Link>
                    <Link :href="services?.wbs" class="block">
                        <Icon
                            class="flex w-full h-12 text-brand-gray hover:text-brand-light"
                            name="wbs"
                        />
                    </Link>
                    <Link :href="services?.tindak_lanjut" class="block">
                        <Icon
                            class="flex w-full h-12 text-brand-gray hover:text-brand-light"
                            name="tindak-lanjut"
                        />
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Icon from '@/Shared/Icon.vue';

const services = computed(() => usePage().props.value?.services);
</script>
