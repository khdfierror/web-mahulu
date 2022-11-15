<script setup>

</script>
<template>
    <img src="/images/pattern_header.svg" alt="" class="absolute w-full top-40 md:top-42 lg:top-32 xl:top-32 xl:w-9/12">
    <div class="screen-header relative max-w-screen-2xl mx-auto flex flex-wrap content-center xlp:pb-40">
        <div class="grid grid-cols-1 content-center md:grid-cols-2 xl:px-20 gap-10">
            <div class="relative text-center md:text-left md:mt-10">
                <div class="flex flex-col px-5 md:px-7 xl:px-0">
                    <h3 class="text-md font-semibold text-gray-500 lg:text-xl xl:text-2xl">Selamat Datang</h3>
                    <h1 class="text-lg font-semibold mt-4 leading-relaxed lg:text-3xl lg:leading-relaxed xl:text-4xl
                        xl:leading-normal">
                        Badan Perencanaan Pembangunan,
                        Penelitian dan Pengembangan Daerah
                        Kabupaten Mahakam Ulu
                    </h1>
                </div>
            </div>
            <div class="relative px-5 order-first md:hidden">
                <img src="/images/mobile_foto_sekda.svg" class="block md:hidden">
            </div>
            <img src="/images/fotosekda.svg"
                class="hidden z-0 w-8/12 absolute right-0 md:block lg:w-8/12 lg:top-16 lg:right-5 xl:w-7/12">
        </div>
    </div>
</template>

<style scoped>
.screen-header {
    min-height: 55vh;
}
</style>