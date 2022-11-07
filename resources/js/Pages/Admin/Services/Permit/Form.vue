<template>
    <AppLayout :title="`${permit?.id ? 'Edit Permit' : 'Create Permit'}`" menu="services.permit">
        <template #header>
            <div>
                <Link
                    :href="route('admin.services.permit.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Permit
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ permit?.id ? permit.title : 'Create Permit' }}
                    </h2>
                </div>
                <div></div>
            </div>
        </template>

        <div class="grid gap-6 mt-6 lg:grid-cols-6 lg:grid-flow-row-dense">
            <div class="lg:col-span-2 lg:col-start-5">
                <div
                    class="bg-white sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20 dark:ring-brand-secondary/30 dark:ring-brand-secondary/30"
                >
                    <div class="space-y-6">
                        <div>
                            <div class="tracking-wider form-label">Created at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ permit?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ permit?.updated_at?.string || '-' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <ButtonPrimary
                        @click="submit"
                        :loading="form.processing"
                        size="lg"
                        class="text-sm"
                    >
                        <icon-fa-regular-save class="w-5 h-5 mr-3" />
                        {{
                            form.processing
                                ? 'Processing...'
                                : permit?.id
                                ? 'Update Permit'
                                : 'Create Permit'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <Applicant
                class="lg:col-span-4"
                @update-applicant-data="updateApplicant"
                :params="form.applicant"
                :errors="form.errors"
            />
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="space-y-6">
                    <!-- <TextInput :error="form.errors.name" v-model="form.name" label="Permit Name" /> -->
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
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonPrimary from '@/Shared/ButtonPrimary.vue';

import TextInput from '@/Shared/TextInput.vue';
import TiptapEditor from '@/Shared/TiptapEditor.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import DateInput from '@/Shared/DateInput.vue';
import TextareaInput from '@/Shared/TextareaInput.vue';
import FileInput from '@/Shared/FileInput.vue';

import Applicant from '../Partials/Applicant.vue';
import { watch } from 'vue';

const props = defineProps({
    permit: Object,
    params: [Object, Array],
});

const form = useForm({
    // name: props.permit?.name || null,
    category: props.permit?.category || null,
    cagar_budaya: props.permit?.cagar_budaya || null,
    start_at: props.permit?.start_at || null,
    end_at: props.permit?.end_at || null,
    file: props.permit?.file || null,
    activity_concept: props.permit?.activity_concept || null,
    applicant: {
        name: props.permit?.applicant?.name || null,
        agency: props.permit?.applicant?.agency || null,
        job: props.permit?.applicant?.job || null,
        phone: props.permit?.applicant?.phone || null,
        email: props.permit?.applicant?.email || null,
        image: props.permit?.applicant?.image || null,

        address: props.permit?.applicant?.address || null,
        province_code: props.permit?.applicant?.province_code || null,
        city_code: props.permit?.applicant?.city_code || null,
        district_code: props.permit?.applicant?.district_code || null,
        ward_code: props.permit?.applicant?.ward_code || null,
    },
});

const updateApplicant = (data) => (form.applicant = { ...data });

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            file: data.file?.object,
            _method: props.permit?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.services.permit.${props.permit?.id ? 'update' : 'store'}`, {
                permit: props.permit,
            })
        );
</script>
