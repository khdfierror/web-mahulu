<template>
    <AppLayout :title="`${data?.id ? 'Edit Data' : 'Create Data'}`" menu="services.data-request">
        <template #header>
            <div>
                <Link
                    :href="route('admin.services.data-request.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Data
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ data?.id ? data.title : 'Create Data' }}
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
                                {{ data?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ data?.updated_at?.string || '-' }}
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
                                : data?.id
                                ? 'Update Data'
                                : 'Create Data'
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
                        :error="form.errors.cagar_budaya"
                        v-model="form.cagar_budaya"
                        :options="params.cagar_budaya"
                        label="Cagar Budaya"
                    />
                    <FileInput
                        :error="form.errors.file"
                        v-model="form.file"
                        type="file"
                        accept="application/pdf"
                        preview-aspect="video"
                        preview-size="full"
                        label="Lampiran Penggunaan Data (PDF)"
                        placeholder="Berkas Lampiran"
                        class="col-span-2"
                    />
                    <TextareaInput
                        :error="form.errors.concept"
                        v-model="form.concept"
                        label="Konsep Penggunaan Data"
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
    data: Object,
    params: [Object, Array],
});

const form = useForm({
    cagar_budaya: props.data?.cagar_budaya || null,
    file: props.data?.file || null,
    concept: props.data?.concept || null,
    applicant: {
        name: props.data?.applicant?.name || null,
        agency: props.data?.applicant?.agency || null,
        job: props.data?.applicant?.job || null,
        phone: props.data?.applicant?.phone || null,
        email: props.data?.applicant?.email || null,
        image: props.data?.applicant?.image || null,

        address: props.data?.applicant?.address || null,
        province_code: props.data?.applicant?.province_code || null,
        city_code: props.data?.applicant?.city_code || null,
        district_code: props.data?.applicant?.district_code || null,
        ward_code: props.data?.applicant?.ward_code || null,
    },
});

const updateApplicant = (data) => (form.applicant = { ...data });

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            file: data.file?.object,
            _method: props.data?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.services.data-request.${props.data?.id ? 'update' : 'store'}`, {
                data: props.data,
            })
        );
</script>
