<script setup>
import WebLayout from '@/Layouts/WebLayout.vue';
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

import Pelapor from './Partials/Pelapor.vue';

const props = defineProps({
    params: [Object, Array],
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
    <WebLayout title="Lapor Penemuan ODCB">
        <div class="bg-brand-light dark:bg-brand-dark px-4 py-16 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div>
                    <Link
                        :href="route('home')"
                        class="flex group items-center align-middle gap-1 hover:text-brand-primary dark:hover:text-brand-light"
                    >
                        <Back class="w-5 h-5 fill-current" />
                        Kembali
                    </Link>
                    <h1 class="mt-2 lg:text-2xl font-semibold">Formulir Pelaporan Gratifikasi</h1>
                </div>
                <div class="mt-6">
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6"
                    >
                        <h1 class="text-base font-semibold col-span-2">A. Identitas Pelapor</h1>
                        <TextInput
                            :error="form.errors.pelapor_nama"
                            v-model="form.pelapor_nama"
                            label="Nama Lengkap"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pelapor_nomor_identitas"
                            v-model="form.pelapor_nomor_identitas"
                            label="NIP / NIK"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pelapor_satker"
                            v-model="form.pelapor_satker"
                            label="Satker / UPT"
                            class="col-span-2"
                        />
                        <TextInput
                            :error="form.errors.pelapor_golongan"
                            v-model="form.pelapor_golongan"
                            label="Jabatan / Golongan / Pangkat"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pelapor_unit_tugas"
                            v-model="form.pelapor_unit_tugas"
                            label="Unit Tugas"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pelapor_email"
                            v-model="form.pelapor_email"
                            label="Email"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pelapor_whatsapp"
                            v-model="form.pelapor_whatsapp"
                            label="Nomor Whatsapp"
                            class="col-span-2 lg:col-span-1"
                        />
                    </div>
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6"
                    >
                        <h1 class="text-base font-semibold col-span-2">
                            B. Data Penerimaan Gratifikasi
                        </h1>
                        <SelectInput
                            :error="form.errors.penerimaan_jenis"
                            v-model="form.penerimaan_jenis"
                            :options="params.jenis"
                            label="Jenis Penerimaan Gratifikasi"
                            class="col-span-2"
                        />
                        <TextareaInput
                            :error="form.errors.penerimaan_uraian"
                            v-model="form.penerimaan_uraian"
                            label="Uraian jenis penerimaan (bentuk, merk, tahun pembuatan, warna, dll)"
                            class="col-span-2"
                        />
                        <TextInput
                            :error="form.errors.penerimaan_nominal"
                            v-model="form.penerimaan_nominal"
                            label="Harga/Nilai/Nominal/Taksiran"
                            class="col-span-2"
                            type="number"
                        />
                        <SelectInput
                            :error="form.errors.penerimaan_peristiwa"
                            v-model="form.penerimaan_peristiwa"
                            :options="params.peristiwa"
                            label="Peristiwa Penerimaan Gratifikasi"
                            class="col-span-2"
                        />
                        <TextInput
                            :error="form.errors.penerimaan_tempat"
                            v-model="form.penerimaan_tempat"
                            label="Tempat Penerimaan Gratifikasi"
                            class="col-span-2 lg:col-span-1"
                        />
                        <DateInput
                            :error="form.errors.penerimaan_tanggal"
                            v-model="form.penerimaan_tanggal"
                            label="Tanggal Penerimaan Gratifikasi"
                            class="col-span-2 lg:col-span-1"
                        />
                    </div>
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6"
                    >
                        <h1 class="text-base font-semibold col-span-2">
                            C. Data Pemberi Gratifikasi
                        </h1>
                        <TextInput
                            :error="form.errors.pemberi_nama"
                            v-model="form.pemberi_nama"
                            label="Nama"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pemberi_pekerjaan"
                            v-model="form.pemberi_pekerjaan"
                            label="Pekerjaan"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pemberi_jabatan"
                            v-model="form.pemberi_jabatan"
                            label="Jabatan"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pemberi_whatsapp"
                            v-model="form.pemberi_whatsapp"
                            label="Nomor Telpon / Whatsapp"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pemberi_email"
                            v-model="form.pemberi_email"
                            label="Email"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextInput
                            :error="form.errors.pemberi_hubungan"
                            v-model="form.pemberi_hubungan"
                            label="Hubungan Dengan Penerima"
                            class="col-span-2 lg:col-span-1"
                        />
                        <TextareaInput
                            :error="form.errors.pemberi_alamat"
                            v-model="form.pemberi_alamat"
                            label="Alamat"
                            class="col-span-2"
                        />
                    </div>
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6"
                    >
                        <h1 class="text-base font-semibold col-span-2">D. Alasan dan Kronologi</h1>
                        <TextareaInput
                            :error="form.errors.kronologi_alasan"
                            v-model="form.kronologi_alasan"
                            label="Alasan pemberian"
                            class="col-span-2"
                        />
                        <TextareaInput
                            :error="form.errors.kronologi_kronologi"
                            v-model="form.kronologi_kronologi"
                            label="Kronologi pemberian (runtutan kejadian penerimaan)"
                            class="col-span-2"
                        />
                        <TextareaInput
                            :error="form.errors.kronologi_catatan"
                            v-model="form.kronologi_catatan"
                            label="Catatan tambahan (bila perlu)"
                            class="col-span-2"
                        />
                        <FileInput
                            :error="form.errors.kronologi_lampiran"
                            v-model="form.kronologi_lampiran"
                            :multiple="true"
                            type="file"
                            preview-aspect="square"
                            preview-size="sm"
                            label="Lampiran"
                            class="col-span-2"
                        />
                    </div>
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6"
                    >
                        <h1 class="text-sm font-semibold col-span-2 text-justify text-brand-dark">
                            Laporan gratifikasi ini saya sampaikan dengan sebenar-benarnya. Apabila
                            ada yang sengaja tidak saya laporkan atau saya laporkan kepada UPG Balai
                            Pelestarian Cagar Budaya Provinsi Kalimantan Timur secara tidak benar,
                            maka saya bersedia mempertanggungjawabkan secara hukum sesuai dengan
                            peraturan perundang-undangan yang berlaku dan saya bersedia memberikan
                            keterangan lebih lanjut.
                        </h1>
                    </div>

                    <div class="mt-8">
                        <ButtonPrimary
                            @click="submit"
                            :loading="form.processing"
                            size="lg"
                            class="text-sm"
                        >
                            <icon-fa-regular-save class="w-5 h-5 mr-3" />
                            Kirim Laporan
                        </ButtonPrimary>
                        <div v-if="form.errors?.gratifikasi" class="form-error py-4 my-1">
                            {{ form.errors?.gratifikasi }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
