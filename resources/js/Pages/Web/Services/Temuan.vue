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

import Pemohon from './Partials/Pemohon.vue';

const props = defineProps({
    params: [Object, Array],
    cities: [Object, Array],
    districts: [Object, Array],
    wards: [Object, Array],
});

const form = useForm({
    category: null,
    files: null,
    name: null,
    condition: null,
    additional_information: null,
    discovered_at: null,

    address: null,
    province_code: null,
    city_code: null,
    district_code: null,
    ward_code: null,

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

const partialReset = (option) => {
    if (option == 'province') {
        form.city_code = null;
        form.district_code = null;
        form.ward_code = null;
    } else if (option == 'city') {
        form.district_code = null;
        form.ward_code = null;
    } else {
        form.ward_code = null;
    }
};

watch(
    () => form.province_code,
    () => {
        Inertia.visit(
            route(`web.services.temuan.create`, {
                province: form.province_code,
            }),
            {
                preserveScroll: true,
                preserveState: true,
                only: ['cities'],
                onSuccess: () => partialReset('province'),
            }
        );
    }
);

watch(
    () => form.city_code,
    () => {
        if (form.city_code !== null) {
            Inertia.visit(
                route(`web.services.temuan.create`, {
                    city: form.city_code,
                }),
                {
                    preserveScroll: true,
                    preserveState: true,
                    only: ['districts'],
                    onSuccess: () => partialReset('district'),
                }
            );
        }
    }
);

watch(
    () => form.district_code,
    () => {
        if (form.district_code !== null) {
            Inertia.visit(
                route(`web.services.temuan.create`, {
                    district: form.district_code,
                }),
                {
                    preserveScroll: true,
                    preserveState: true,
                    only: ['wards'],
                }
            );
        }
    }
);

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            files: data.files?.map((item) => ({ file: item.object })),
            _method: 'POST',
        }))
        .post(route(`web.services.temuan.store`), {
            preserveState: (page) => Object.keys(page.props.errors).length,
            preserveScroll: (page) => Object.keys(page.props.errors).length,
            onSuccess: () => {
                form.reset();
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
                    <h1 class="mt-2 lg:text-2xl font-semibold">Lapor Penemuan ODCB</h1>
                </div>
                <div class="mt-6">
                    <Pemohon
                        @update-applicant-data="updateApplicant"
                        :params="form.applicant"
                        :errors="form.errors"
                    />
                    <div
                        class="grid grid-cols-2 bg-white gap-6 px-6 py-4 sm:rounded-lg ring-1 ring-brand-secondary/[0.05] mt-6"
                    >
                        <TextInput
                            :error="form.errors.name"
                            v-model="form.name"
                            label="Nama Temuan / Objek"
                            class="col-span-2"
                        />
                        <SelectInput
                            :error="form.errors.category"
                            v-model="form.category"
                            :options="params.categories"
                            label="Kategori Cagar Budaya"
                        />
                        <DateInput
                            :error="form.errors.discovered_at"
                            v-model="form.discovered_at"
                            placeholder="Tanggal Ditemukan"
                            label="Tanggal Ditemukan"
                        />
                        <TextareaInput
                            :error="form.errors.condition"
                            v-model="form.condition"
                            label="Kondisi Benda Temuan"
                            class="col-span-2"
                        />
                        <TextareaInput
                            :error="form.errors.additional_information"
                            v-model="form.additional_information"
                            label="Keterangan Tambahan"
                            class="col-span-2"
                        />
                        <FileInput
                            :error="form.errors.files"
                            v-model="form.files"
                            :multiple="true"
                            type="file"
                            preview-aspect="square"
                            preview-size="sm"
                            label="Lampiran (Foto Temuan / Laporan Singkat)"
                            class="col-span-2"
                        />

                        <SelectInput
                            :error="form.errors.province_code"
                            v-model="form.province_code"
                            :options="params.provinces"
                            label="Provinsi"
                        />
                        <SelectInput
                            :error="form.errors.city_code"
                            v-model="form.city_code"
                            :options="cities"
                            label="Kabupaten / Kota"
                        />
                        <SelectInput
                            :error="form.errors.district_code"
                            v-model="form.district_code"
                            :options="districts"
                            label="Kecamatan"
                        />
                        <SelectInput
                            :error="form.errors.ward_code"
                            v-model="form.ward_code"
                            :options="wards"
                            label="Kelurahan"
                        />
                        <TextareaInput
                            class="col-span-2"
                            :error="form.errors.address"
                            v-model="form.address"
                            label="Alamat"
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
                        <div v-if="form.errors?.temuan" class="form-error py-4 my-1">
                            {{ form.errors?.temuan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
