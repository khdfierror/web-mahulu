<template>
    <AppLayout
        :title="`${agenda?.id ? 'Edit Agenda' : 'Create Agenda'}`"
        menu="agenda"
    >
        <template #header>
            <div>
                <Link
                    :href="route('admin.agenda.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Agenda
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ agenda?.id ? agenda.title : 'Create Agenda' }}
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
                                {{ agenda?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ agenda?.updated_at?.string || '-' }}
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
                                : agenda?.id
                                ? 'Update Agenda'
                                : 'Create Agenda'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="space-y-6">
                    <TextInput
                        :error="form.errors.title"
                        v-model="form.title"
                        :options="params.title"
                        label="Judul Kegiatan"
                    />
                   
                    <DateInput
                        :error="form.errors.date"
                        v-model="form.date"
                        placeholder="Tanggal Kegiatan"
                        label="Tanggal Kegiatan"
                    />
                    <TextInput
                        :error="form.errors.location"
                        v-model="form.location"
                        :options="params.location"
                        label="Tempat"
                        help="Misal: Hotel Aston Samarinda"
                    />
                    <TextInput
                        :error="form.errors.participant"
                        v-model="form.participant"
                        :options="params.participant"
                        label="Peserta"
                        help="Dihadiri oleh"
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
import DateInput from '@/Shared/DateInput.vue';


import { watch } from 'vue';

const props = defineProps({
    agenda: Object,
    params: [Object, Array],
});

const form = useForm({
    title: props.agenda?.title || null,
    date: props.agenda?.date || null,
    location: props.agenda?.location || null,
    participant: props.agenda?.participant || null,
});

const updateApplicant = (data) => (form.applicant = { ...data });

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            file: data.file?.object,
            _method: props.agenda?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.agenda.${props.agenda?.id ? 'update' : 'store'}`, {
                agenda: props.agenda,
            })
        );
</script>
