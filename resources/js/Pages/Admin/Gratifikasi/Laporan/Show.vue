<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Back from '~icons/ri/arrow-left-fill';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import ButtonPrimary from '@/Shared/ButtonPrimary.vue';

import TextInput from '@/Shared/TextInput.vue';
import TiptapEditor from '@/Shared/TiptapEditor.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import DateInput from '@/Shared/DateInput.vue';
import TextareaInput from '@/Shared/TextareaInput.vue';
import FileInput from '@/Shared/FileInput.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    params: [Object, Array],
    laporan: [Object, Array],
});

const form = useForm({
    pelapor_nama: null,
    pelapor_nomor_identitas: null,
    pelapor_golongan: null,
    pelapor_satker: null,
    pelapor_unit_tugas: null,
    pelapor_email: null,
    pelapor_whatsapp: null,

    penerimaan_jenis: null,
    penerimaan_uraian: null,
    penerimaan_nominal: null,
    penerimaan_peristiwa: null,
    penerimaan_tanggal: null,
    penerimaan_tempat: null,

    pemberi_nama: null,
    pemberi_pekerjaan: null,
    pemberi_jabatan: null,
    pemberi_alamat: null,
    pemberi_whatsapp: null,
    pemberi_email: null,
    pemberi_hubungan: null,

    kronologi_alasan: null,
    kronologi_kronologi: null,
    kronologi_catatan: null,
    kronologi_lampiran: null,
});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            kronologi_lampiran: data.kronologi_lampiran?.map((item) => item.object),
            _method: 'POST',
        }))
        .post(route(`web.services.gratifikasi.store`), {
            preserveState: (page) => Object.keys(page.props.errors).length,
            // preserveScroll: (page) => Object.keys(page.props.errors).length,
            onSuccess: () => {
                form.reset();
                // resetPelapor()
            },
        });
</script>

<template>
    <app-layout :title="params?.title" menu="gratifikasi.laporan">
        <template #header>
            <div>
                <Link
                    :href="route('admin.gratifikasi.laporan.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />{{ params?.title }}
                </Link>
            </div>
        </template>
        <div class="">
            <div class="inline-block min-w-full py-2 align-middle overflow-x-hidden">
                <div class="mt-6 min-w-full">
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6 dark:bg-brand-secondary/20 dark:ring-brand-secondary/30"
                    >
                        <h1 class="text-base font-semibold col-span-2">A. Identitas Pelapor</h1>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Nama Lengkap</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.pelapor?.nama }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">NIP / NIK</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.pelapor?.nomor_identitas }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Nomor Whatsapp</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.pelapor?.whatsapp }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Email</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.pelapor?.email }}
                            </span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Satker / UPT</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.pelapor?.satker || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Jabatan / Golongan / Pangkat</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.pelapor?.golongan || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Unit Tugas</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.pelapor?.unit_tugas || '-' }}
                            </span>
                        </div>
                    </div>
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6 dark:bg-brand-secondary/20 dark:ring-brand-secondary/30"
                    >
                        <h1 class="text-base font-semibold col-span-2">
                            B. Data Penerimaan Gratifikasi
                        </h1>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Jenis Penerimaan Gratifikasi</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.jenis }}
                            </span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for=""
                                >Uraian jenis penerimaan (bentuk, merk, tahun pembuatan, warna,
                                dll)</label
                            >
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.uraian || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Harga/Nilai/Nominal/Taksiran</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ n(laporan.nominal) }}
                            </span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Peristiwa Penerimaan Gratifikasi</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.peristiwa || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Tempat Penerimaan Gratifikasi</label>
                            <span
                                class="border p-2 dark:border-brand-light border-brand-dark rounded"
                            >
                                {{ laporan.tempat || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Tanggal Penerimaan Gratifikasi</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.tanggal?.short }}
                            </span>
                        </div>
                    </div>
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6 dark:bg-brand-secondary/20 dark:ring-brand-secondary/30"
                    >
                        <h1 class="text-base font-semibold col-span-2">
                            C. Data Pemberi Gratifikasi
                        </h1>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Nama</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.pemberi?.nama }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Nomor Telpon / Whatsapp</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.pemberi?.whatsapp }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Email</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.pemberi?.email }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Pekerjaan</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.pemberi?.pekerjaan || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Jabatan</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.pemberi?.jabatan || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex flex-col gap-1">
                            <label for="">Hubungan Dengan Penerima</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.pemberi?.hubungan || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Alamat</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.pemberi?.alamat || '-' }}
                            </span>
                        </div>
                    </div>
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6 dark:bg-brand-secondary/20 dark:ring-brand-secondary/30"
                    >
                        <h1 class="text-base font-semibold col-span-2">D. Alasan dan Kronologi</h1>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Alasan Pemberian</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.kronologi?.alasan || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Kronologi pemberian (runtutan kejadian penerimaan)</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.kronologi?.kronologi || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Catatan tambahan</label>
                            <span
                                class="border p-2 border-brand-dark dark:border-brand-light rounded"
                            >
                                {{ laporan.kronologi?.catatan || '-' }}
                            </span>
                        </div>
                        <div class="col-span-2 flex flex-col gap-1">
                            <label for="">Lampiran</label>
                            <div
                                class="border p-2 border-brand-dark dark:border-brand-light rounded flex flex-col divide-y dark:divide-brand-light divide-brand-dark"
                            >
                                <a
                                    :href="item.url"
                                    v-for="(item, index) in laporan.kronologi?.lampiran"
                                    :key="index"
                                    class="py-2 inline-flex items-center hover:text-brand-primary"
                                >
                                    <icon-ri-download-line class="w-5 h-5 mr-3" />
                                    {{ item.name }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
