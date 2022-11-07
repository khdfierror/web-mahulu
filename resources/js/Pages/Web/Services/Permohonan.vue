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
});

const form = useForm({
    cagar_budaya: null,
    konsep: null,
    files: null,

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
            files: data.files?.map((item) => item.object),
            _method: 'POST',
        }))
        .post(route(`web.services.permohonan.store`), {
            preserveState: (page) => Object.keys(page.props.errors).length,
            preserveScroll: (page) => Object.keys(page.props.errors).length,
            onSuccess: () => {
                form.reset();
            },
        });
</script>

<template>
    <WebLayout :title="params.title">
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
                    <h1 class="mt-2 lg:text-2xl font-semibold">{{ params.title }}</h1>
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
                        <SelectInput
                            :error="form.errors.cagar_budaya"
                            v-model="form.cagar_budaya"
                            :options="params.cagar_budaya"
                            label="Cagar Budaya"
                            class="col-span-2"
                        />
                        <TextareaInput
                            :error="form.errors.konsep"
                            v-model="form.konsep"
                            label="Konsep Penggunaan Data"
                            class="col-span-2"
                        />
                        <FileInput
                            :error="form.errors.files"
                            v-model="form.files"
                            :multiple="true"
                            type="file"
                            preview-aspect="square"
                            preview-size="sm"
                            accept=".pdf"
                            label="Lampiran Penggunaan Data (Mengunggah PDF)"
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
                        <div v-if="form.errors?.permohonan" class="form-error py-4 my-1">
                            {{ form.errors?.permohonan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>
