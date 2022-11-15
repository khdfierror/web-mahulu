<script setup>

</script>
<template>
    <div class="-mb-2 relative max-w-screen-2xl py-2 mx-auto xl:px-16 xl:mt-44">
        <div class="lg:w-3/5 lg:py-14 xl:w-3/5 w-full bg-brand-secondary py-10 mx-auto bg-pattern">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="px-5 space-y-3 text-white text-center xl:text-left xl:px-10">
                    <p>Pengunjung</p>
                    <h1 class="text-4xl font-semibold">1,346,950</h1>
                </div>
                <div class="px-5 space-y-3 text-white text-center xl:text-left">
                    <p>Klik</p>
                    <h1 class="text-4xl font-semibold">41,732</h1>
                </div>
                <div class="px-5 space-y-3 text-white text-center xl:text-left">
                    <p>Hari Ini</p>
                    <h1 class="text-4xl font-semibold">88,137</h1>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.bg-pattern {
    background-image: url('/images/pattern_info.png');
    background-size: contain;
    background-repeat: no-repeat;
    border-radius: 65px 65px 0px 0px;
}
</style>