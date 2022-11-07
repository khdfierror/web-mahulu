<template>
    <AppLayout
        :title="`${peristiwa?.id ? 'Edit Peristiwa Penerimaan' : 'Create Peristiwa Penerimaan'}`"
        menu="gratifikasi.peristiwa"
    >
        <template #header>
            <div>
                <Link
                    :href="route('admin.gratifikasi.peristiwa.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />{{ params?.title }}
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ peristiwa?.id ? peristiwa.title : 'Create Peristiwa' }}
                    </h2>
                </div>
                <div></div>
            </div>
        </template>

        <div class="grid gap-6 mt-6 lg:grid-cols-6 lg:grid-flow-row-dense">
            <div class="lg:col-span-2 lg:col-start-5">
                <div
                    class="bg-white sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20 dark:ring-brand-secondary/30"
                >
                    <div class="space-y-6">
                        <div>
                            <div class="tracking-wider form-label">Created at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ peristiwa?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ peristiwa?.updated_at?.string || '-' }}
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
                                : peristiwa?.id
                                ? 'Update Peristiwa'
                                : 'Create Peristiwa'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="space-y-6">
                    <TextInput
                        :error="form.errors.nama"
                        v-model="form.nama"
                        label="Nama Peristiwa"
                    />
                    <TextInput
                        :error="form.errors.kode"
                        v-model="form.kode"
                        label="Kode Peristiwa"
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
import { watch } from 'vue';

const props = defineProps({
    peristiwa: Object,
    params: [Object, Array],
});

const form = useForm({
    nama: props.peristiwa?.nama || null,
    kode: props.peristiwa?.kode || null,
});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            _method: props.peristiwa?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.gratifikasi.peristiwa.${props.peristiwa?.id ? 'update' : 'store'}`, {
                peristiwa: props.peristiwa,
            })
        );
</script>
