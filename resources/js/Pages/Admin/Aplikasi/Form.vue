<template>
    <AppLayout :title="`${aplikasi?.id ? 'Edit Aplikasi' : 'Create Aplikasi'}`" menu="aplikasi">
        <template #header>
            <div>
                <Link :href="route('admin.aplikasi.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary">
                <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Aplikasi
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ aplikasi?.id ? aplikasi.name : 'Create Aplikasi' }}
                    </h2>
                </div>
                <div></div>
            </div>
        </template>

        <div class="grid gap-6 mt-6 lg:grid-cols-6 lg:grid-flow-row-dense">
            <div class="lg:col-span-2 lg:col-start-5">
                <div
                    class="bg-white sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20 dark:ring-brand-secondary/30 dark:ring-brand-secondary/30">
                    <div class="space-y-6">
                        <div>
                            <div class="tracking-wider form-label">Created at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ aplikasi?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ aplikasi?.updated_at?.string || '-' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <ButtonPrimary @click="submit" :loading="form.processing" size="lg" class="text-sm">
                        <icon-fa-regular-save class="w-5 h-5 mr-3" />
                        {{
                                form.processing
                                    ? 'Processing...'
                                    : aplikasi?.id
                                        ? 'Update Data'
                                        : 'Create Data'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20">
                <div class="space-y-6">
                    <TextInput :error="form.errors.name" v-model="form.name" :options="params.name"
                        label="Nama Aplikasi" class="col-span-2" />

                    <FileInput :error="form.errors.image" v-model="form.image" type="image" preview-aspect="video"
                        preview-size="full" label="Logo Aplikasi" placeholder="Logo" class="col-span-2" />

                    <TextInput :error="form.errors.link" v-model="form.link" label="Link" />
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
import FileInput from '@/Shared/FileInput.vue';


import { watch } from 'vue';

const props = defineProps({
    aplikasi: Object,
    params: [Object, Array],
});

const form = useForm({
    name: props.aplikasi?.name || null,
    image: props.aplikasi?.image || null,
    link: props.aplikasi?.link || null,


});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            image: data.image?.object,
            _method: props.aplikasi?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.aplikasi.${props.aplikasi?.id ? 'update' : 'store'}`, {
                aplikasi: props.aplikasi,
            })
        );
</script>
