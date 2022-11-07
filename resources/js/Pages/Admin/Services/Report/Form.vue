<template>
    <AppLayout :title="`${report?.id ? 'Edit Report' : 'Create Report'}`" menu="services.report">
        <template #header>
            <div>
                <Link
                    :href="route('admin.services.case.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Laporan Penemuan
                    ODCB
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ report?.id ? report.title : 'Create Report' }}
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
                                {{ report?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ report?.updated_at?.string || '-' }}
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
                                : report?.id
                                ? 'Update Report'
                                : 'Create Report'
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
                    <SelectInput
                        :error="form.errors.category"
                        v-model="form.category"
                        :options="params.categories"
                        label="Kategori Cagar Budaya"
                    />
                    <SelectInput
                        :error="form.errors.cagar_budaya"
                        v-model="form.cagar_budaya"
                        :options="params.cagar_budaya"
                        label="Cagar Budaya"
                    />
                    <TextareaInput
                        :error="form.errors.condition"
                        v-model="form.condition"
                        label="Kondisi Terkini Cagar Budaya"
                    />
                    <FileInput
                        :error="form.errors.image"
                        v-model="form.image"
                        type="image"
                        preview-aspect="video"
                        preview-size="full"
                        label="Foto Cagar Budaya Yang Mengalami Kasus"
                        placeholder="Foto"
                        class="col-span-2"
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
    report: Object,
    params: [Object, Array],
});

const form = useForm({
    category: props.report?.category || null,
    cagar_budaya: props.report?.cagar_budaya || null,
    image: props.report?.image || null,
    condition: props.report?.condition || null,
    reported_at: props.report?.reported_at || null,

    address: props.report?.address || null,
    province_code: props.report?.province_code || null,
    city_code: props.report?.city_code || null,
    district_code: props.report?.district_code || null,
    ward_code: props.report?.ward_code || null,

    applicant: {
        name: props.report?.applicant?.name || null,
        agency: props.report?.applicant?.agency || null,
        job: props.report?.applicant?.job || null,
        phone: props.report?.applicant?.phone || null,
        email: props.report?.applicant?.email || null,
        image: props.report?.applicant?.image || null,

        address: props.report?.applicant?.address || null,
        province_code: props.report?.applicant?.province_code || null,
        city_code: props.report?.applicant?.city_code || null,
        district_code: props.report?.applicant?.district_code || null,
        ward_code: props.report?.applicant?.ward_code || null,
    },
});

const updateApplicant = (data) => (form.applicant = { ...data });

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            image: data.image?.object,
            _method: props.report?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.services.case.${props.report?.id ? 'update' : 'store'}`, {
                report: props.offense,
            })
        );
</script>
