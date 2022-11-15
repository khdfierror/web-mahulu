<script setup>

const popularPost = [
    {
        title: 'PEMKAB MAHULU TERIMA PENGHARGAAN DARI KEMENKEU RI ATAS OPINI WTP',
        href: '#',
        category: { name: 'Article', href: '#' },
        description:
            'SAMARINDA – Pemerintah Kabupaten (Pemkab)  Mahakam Ulu (Mahulu) menerima Penghargaan Atas Keberhasilan Menyusun dan Menyajikan  Laporan Keuangan TA 2020  dengan capaian Opini Wajar Tanpa Pengecualian (WTP) dari Pemerintah Republik Indonesia Menteri Keuangan Sri Mulyani Indrawati. ipsum dolor sit amet consectetur adipisicing elit. Architecto accusantium praesentium eius, ut atque fuga culpa, similique sequi cum eos quis dolorumSAMARINDA – Pemerintah Kabupaten (Pemkab)  Mahakam Ulu (Mahulu) menerima Penghargaan Atas Keberhasilan Menyusun dan Menyajikan  Laporan Keuangan TA 2020  dengan capaian Opini Wajar Tanpa Pengecualian (WTP) dari Pemerintah Republik Indonesia Menteri Keuangan Sri Mulyani Indrawati..',
        date: 'Mar 16, 2020',
        datetime: '2020-03-16',
        imageUrl:
            'https://humas.mahakamulukab.go.id/wp-content/uploads/2021/12/WhatsApp-Image-2021-12-06-at-18.49.18.jpeg',
        readingTime: '6 min',
    }
]
const posts = [
    {
        title: 'Bapelitbangda Gelar Rakor Persiapan Forum Satu Data se Mahakam Ulu',
        href: '#',
        category: { name: 'Article', href: '#' },
        description:
            'UJOH BILANG – Dalam rangka mendukung perencanaan, pelaksanaan, evaluasi dan pengendalian pembangunan melalui penyediaan basis data yang akurat, terpadu dan terpusat, Pemerintah Kabupaten Mahakam Ulu (Pemkab Mahulu) melalui Badan Perencanaan, Pembangunan, Penelitian dan Pengembangan Daerah (Bappelitbangda) menggelar rapat koordinasi Persiapan Forum Satu Data Mahakam Ulu yang berlangsung di ruang rapat Bappelitbangda..',
        date: 'Mar 16, 2020',
        datetime: '2020-03-16',
        imageUrl:
            'https://humas.mahakamulukab.go.id/wp-content/uploads/2021/11/WhatsApp-Image-2021-11-24-at-15.14.10-2.jpeg',
        readingTime: '6 min',
    },
    {
        title: 'Selesaikan Kuliah Bawa Ijazah Dengan Nilai Terbaik ',
        href: '#',
        category: { name: 'Video', href: '#' },
        description:
            'UJOH BILANG – Pemerintah Kabupaten Mahakam Ulu memberikan perhatian serius kepada generasi  muda dalam bidang Pendidikan, yaitu dengan Program Gerbang Cerdas Mahulu (GCM).',
        date: 'Mar 10, 2020',
        datetime: '2020-03-10',
        imageUrl:
            'https://humas.mahakamulukab.go.id/wp-content/uploads/2021/10/WhatsApp-Image-2021-10-11-at-12.52.53.jpeg',
        readingTime: '4 min',
    },
    {
        title: 'Bupati : Manfaatkan SDM dan Kearifan Lokal, Majukan Daerah dan Tingkatkan Ekonomi Masyarakat MAHULU',
        href: '#',
        category: { name: 'Case Study', href: '#' },
        description:
            'UJOH BILANG – Salah satu program pemerintah dalam upaya mendorong kemajuan daerah dan meningkatkan ekonomi masyarakat yaitu program dibidang Pariwisata melalui pembangunan objek wisata serta rencana pembangunan Balai kerajinan Seni Ukir dan Anyaman di Kabupaten Mahakam Ulu (Mahulu).',
        date: 'Feb 12, 2020',
        datetime: '2020-02-12',
        imageUrl:
            'https://humas.mahakamulukab.go.id/wp-content/uploads/2021/06/2-6-1536x1052.jpg',
        readingTime: '11 min',
    },
]
</script>
<template>
    <div class="relative max-w-screen-2xl py-2 mx-auto xl:px-16">
        <div class="z-20 w-full flex justify-between py-6 mb-4 px-5">
            <h1 class="text-2xl font-semibold">Berita terbaru</h1>
            <button type="button" border class="border-gray-100 px-4 py-2 rounded-xl hover:bg-gray-100">
                Lihat Semua
            </button>
        </div>
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 md:gap-2 lg:grid-cols-2 xl:grid-cols-2">
            <div class="relative w-full min-h-96 px-5">
                <div v-for="post in popularPost" :key="post.title" class="flex flex-col overflow-hidden z-50">
                    <div class="flex-shrink-0">
                        <img class="h-46 w-full object-cover object-top rounded-2xl md:h-60 xl:h-96" :src="post.imageUrl"
                            alt="" />
                    </div>
                    <div class="flex-1 bg-brand-light py-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <a :href="post.href" class="block mt-2">
                                <p class="text-xl uppercase font-semibold text-gray-900 md:text-lg lg:text-xl">
                                    {{ post.title }}
                                </p>
                                <p class="mt-3 text-base text-gray-500 md:text-sm">
                                    {{ post.description.slice(0, 180) }}
                                </p>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="w-full px-5 md:px-0 md:pr-5">
                <div class="flex mb-2 md:mb-5 xl:mb-7" v-for="post in posts" :key="post.title">
                    <div class="mr-2 flex-shrink-0">
                        <img :src="post.imageUrl" class="w-24 h-24 object-cover rounded-xl md:w-32 xl:w-44 xl:h-full">
                    </div>
                    <div class="px-2">
                        <h4 class="text-sm font-semibold capitalize lg:text-lg">{{ post.title }}</h4>
                        <p class="mt-1 text-xs mt-2">
                            {{ post.description.slice(0, 150) }}...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.card-rounded {
    border-radius: 65px;
}
</style>