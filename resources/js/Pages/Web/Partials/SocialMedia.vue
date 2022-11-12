<script setup>
const docs = [
    {
        date: '30 Desember 2013',
        image: '/images/books/01.png'
    },
    {
        date: '30 Desember 2013',
        image: '/images/books/02.png'
    },
    {
        date: '30 Desember 2013',
        image: '/images/books/03.png'
    },
    {
        date: '30 Desember 2013',
        image: '/images/books/01.png'
    },
]
</script>
<template>
    <div class="relative max-w-screen-2xl py-2 pb-10 mx-auto xl:px-16">
        <div class="w-full flex justify-between py-6 mb-4 px-5">
            <h1 class="text-2xl font-semibold">Dokumen</h1>
            <button type="button" border class="border-gray-100 px-4 py-2 rounded-xl hover:bg-gray-100">
                Lihat Semua
            </button>
        </div>
        <div class="w-full px-5">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4 xl:gap-10">
                <div class="w-full py-14 bg-green-100 rounded-xl" v-for="item in docs" :key="item.key">
                    <img :src="item.image" alt="" class="mx-auto shadow-md shadow-green-100">
                </div>
            </div>
        </div>
    </div>

</template>