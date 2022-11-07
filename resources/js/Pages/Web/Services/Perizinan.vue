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

import Pemohon from './Partials/Pemohon.vue';

const props = defineProps({
    params: [Object, Array],
});

const form = useForm({
    category: null,
    cagar_budaya: null,
    start_at: null,
    end_at: null,
    file: null,
    activity_concept: null,
    applicant: {
        name: null,
        agency: null,
        job: null,
        phone: null,
        email: null,
        image: null,

        address: null,
        province_code: null,
        city_code: null,
        district_code: null,
        ward_code: null,
    },
});

const updateApplicant = (data) => (form.applicant = { ...data });

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            file: data.file?.object,
            _method: 'POST',
        }))
        .post(route(`web.services.perizinan.store`), {
            preserveState: (page) => Object.keys(page.props.errors).length,
            preserveScroll: (page) => Object.keys(page.props.errors).length,
            onSuccess: () => {
                form.reset();
            },
        });
</script>

<template>
    <WebLayout title="Izin Pemanfaatan Cagar Budaya">
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
                    <h1 class="mt-2 lg:text-2xl font-semibold">Izin Pemanfaatan Cagar Budaya</h1>
                </div>
                <div class="mt-6">
                    <Pemohon
                        @update-applicant-data="updateApplicant"
                        :params="form.pemohon"
                        :errors="form.errors"
                    />
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6"
                    >
                        <SelectInput
                            :error="form.errors.category"
                            v-model="form.category"
                            :options="params.categories"
                            label="Kategori Izin"
                        />
                        <SelectInput
                            :error="form.errors.cagar_budaya"
                            v-model="form.cagar_budaya"
                            :options="params.cagar_budaya"
                            label="Cagar Budaya"
                        />
                        <DateInput
                            :error="form.errors.start_at"
                            v-model="form.start_at"
                            placeholder="Tanggal Mulai"
                            label="Tanggal Mulai"
                        />
                        <DateInput
                            :error="form.errors.end_at"
                            v-model="form.end_at"
                            placeholder="Tanggal Selesai"
                            label="Tanggal Selesai"
                        />
                        <FileInput
                            :error="form.errors.file"
                            v-model="form.file"
                            type="file"
                            preview-aspect="video"
                            preview-size="full"
                            label="Berkas lampiran (konsep kegiatan, jadwal acara dapat disertakan dilampiran)"
                            placeholder="Berkas Lampiran"
                            class="col-span-2"
                        />
                        <TextareaInput
                            :error="form.errors.activity_concept"
                            v-model="form.activity_concept"
                            label="Konsep Kegiatan"
                            placeholder="Ceritakan konsep kegiatan yang akan diadakan"
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
                            Ajukan Permohonan
                        </ButtonPrimary>
                        <div v-if="form.errors?.perizinan" class="form-error py-4 my-1">
                            {{ form.errors?.perizinan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
