<template>
    <div
        class="px-6 py-4 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
    >
        <h3 class="text-lg font-medium">Data Diri Pemohon</h3>
        <!-- <h3 class="text-lg font-medium">Applicant Data</h3> -->
        <div class="grid grid-cols-2 gap-6 mt-6">
            <TextInput
                :error="form.errors?.name"
                v-model="form.name"
                label="Nama Lengkap"
                class="col-span-2"
            />
            <TextInput
                :error="form.errors?.agency"
                v-model="form.agency"
                label="Instansi"
                class="col-span-2"
            />
            <TextInput
                :error="form.errors?.job"
                v-model="form.job"
                label="Pekerjaan"
                class="col-span-2"
            />
            <TextInput
                :error="form.errors?.phone"
                v-model="form.phone"
                label="No Telp / Whatsapp"
                class="col-span-1"
            />
            <TextInput
                :error="form.errors?.email"
                v-model="form.email"
                label="Email"
                class="col-span-1"
            />
            <FileInput
                :error="form.errors.image"
                v-model="form.image"
                type="image"
                preview-aspect="video"
                preview-size="2xl"
                label="Foto Identitas (KTP/SIM)"
                class="col-span-2"
            />

            <!-- <SelectInput
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
            />-->
            <TextareaInput
                class="col-span-2"
                :error="form.errors.address"
                v-model="form.address"
                label="Alamat"
            />
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { watchDebounced } from '@vueuse/core';

import ButtonPrimary from '@/Shared/ButtonPrimary.vue';
import TextareaInput from '@/Shared/TextareaInput.vue';
import SelectInput from '@/Shared/SelectInput.vue';

const emit = defineEmits(['updateApplicantData']);

const props = defineProps({
    params: Object,
    errors: Object,
});

const form = useForm({
    name: props.params?.name || '',
    agency: props.params?.agency || '',
    job: props.params?.job || '',
    phone: props.params?.phone || '',
    email: props.params?.email || '',
    image: props.params?.image || '',

    address: props.params?.address || null,
    province_code: props.params?.province_code || null,
    city_code: props.params?.city_code || null,
    district_code: props.params?.district_code || null,
    ward_code: props.params?.ward_code || null,
});

watchDebounced(
    form,
    (value) => {
        console.log(form.image);
        emit('updateApplicantData', {
            name: form.name,
            agency: form.agency,
            job: form.job,
            phone: form.phone,
            email: form.email,
            image: form.image?.object,
            address: form.address,
            province_code: form.province_code,
            city_code: form.city_code,
            district_code: form.district_code,
            ward_code: form.ward_code,
        });
    },
    { debounce: 300 }
);

watch(
    () => props.errors,
    (errors) => {
        Object.keys(props.errors)
            .filter((err) => err.startsWith('applicant'))
            .forEach((element) => {
                let field = element.replace('applicant.', '');
                let errorMessage = props.errors[element].replace('applicant.', '');
                form.setError(field, errorMessage);
            });
    },
    {
        deep: true,
    }
);
</script>
