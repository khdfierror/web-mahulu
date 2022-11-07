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
    kategori: null,
    what: null,
    where: null,
    when: null,
    who: null,
    how: null,

    files: null,

    pelapor: {
        nama: null,
        nomor_identitas: null,
        whatsapp: null,
        email: null,
    },
});

const resetPelapor = () => {
    form.pelapor = {
        nama: null,
        nomor_identitas: null,
        whatsapp: null,
        email: null,
    };
};

const updatePelaporData = (data) => (form.pelapor = { ...data });

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            files: data.files?.map((item) => ({ file: item.object })),
            _method: 'POST',
        }))
        .post(route(`web.services.wbs.store`), {
            preserveState: (page) => Object.keys(page.props.errors).length,
            preserveScroll: (page) => Object.keys(page.props.errors).length,
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
                    <h1 class="mt-2 lg:text-2xl font-semibold">
                        Whistleblowing System BPCB Provinsi Kalimantan Timur
                    </h1>
                    <h2 class="mt-2 lg:text-lg">
                        Sarana pengaduan dugaan tindakan penyimpangan dan pelanggaran yang dilakukan
                        oleh dan dari pegawai BPCB Provinsi Kalimantan Timur
                    </h2>
                </div>
                <div class="mt-6">
                    <Pelapor
                        @update-pelapor-data="updatePelaporData"
                        :params="form.pelapor"
                        :errors="form.errors"
                    />
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6"
                    >
                        <SelectInput
                            :error="form.errors.kategori"
                            v-model="form.kategori"
                            :options="params.categories"
                            label="Kategori Aduan"
                            class="col-span-2"
                        />
                        <TextareaInput
                            :error="form.errors.what"
                            v-model="form.what"
                            label="Perbuatan berindikasi penyimpangan dan pelanggaran yang diketahui? (ceritakan detail aduan)"
                            class="col-span-2"
                            help="Unsur Aduan (*What)"
                        />
                        <TextareaInput
                            :error="form.errors.where"
                            v-model="form.where"
                            label="Dimana perbuatan tersebut dilakukan?"
                            class="col-span-2"
                            help="Unsur Aduan (*Where)"
                        />
                        <TextareaInput
                            :error="form.errors.when"
                            v-model="form.when"
                            label="Kapan perbuatan tersebut dilakukan? (sebutkan tanggal dan waktu kejadian)"
                            class="col-span-2"
                            help="Unsur Aduan (*When)"
                        />
                        <TextareaInput
                            :error="form.errors.who"
                            v-model="form.who"
                            label="Siapa saja yang terlibat dalam perbuatan tersebut?"
                            class="col-span-2"
                            help="Unsur Aduan (*Who)"
                        />
                        <TextareaInput
                            :error="form.errors.how"
                            v-model="form.how"
                            label="Bagaimana perbuatan tersebut dilakukan? (jelaskan detil modus, cara, dsb)"
                            class="col-span-2"
                            help="Unsur Aduan (*How)"
                        />

                        <FileInput
                            :error="form.errors.files"
                            v-model="form.files"
                            :multiple="true"
                            type="file"
                            preview-aspect="square"
                            preview-size="sm"
                            label="Lampiran"
                            class="col-span-2"
                        />
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
                        <div v-if="form.errors?.wbs" class="form-error py-4 my-1">
                            {{ form.errors?.wbs }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
