<template>
    <AppLayout :title="`${offense?.id ? 'Edit Case' : 'Create Case'}`" menu="services.case">
        <template #header>
            <div>
                <Link
                    :href="route('admin.services.case.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Pelaporan Kasus
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ offense?.id ? offense.title : 'Create Case' }}
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
                                {{ offense?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ offense?.updated_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Reported at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ offense?.reported_at?.string || '-' }}
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
                                : offense?.id
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
    offense: Object,
    params: [Object, Array],
});

const form = useForm({
    category: props.offense?.category || null,
    cagar_budaya: props.offense?.cagar_budaya || null,
    image: props.offense?.image || null,
    condition: props.offense?.condition || null,
    reported_at: props.offense?.reported_at || null,

    address: props.offense?.address || null,
    province_code: props.offense?.province_code || null,
    city_code: props.offense?.city_code || null,
    district_code: props.offense?.district_code || null,
    ward_code: props.offense?.ward_code || null,

    applicant: {
        name: props.offense?.applicant?.name || null,
        agency: props.offense?.applicant?.agency || null,
        job: props.offense?.applicant?.job || null,
        phone: props.offense?.applicant?.phone || null,
        email: props.offense?.applicant?.email || null,
        image: props.offense?.applicant?.image || null,

        address: props.offense?.applicant?.address || null,
        province_code: props.offense?.applicant?.province_code || null,
        city_code: props.offense?.applicant?.city_code || null,
        district_code: props.offense?.applicant?.district_code || null,
        ward_code: props.offense?.applicant?.ward_code || null,
    },
});

const updateApplicant = (data) => (form.applicant = { ...data });

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            image: data.image?.object,
            _method: props.offense?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.services.case.${props.offense?.id ? 'update' : 'store'}`, {
                offense: props.offense,
            })
        );
</script>
