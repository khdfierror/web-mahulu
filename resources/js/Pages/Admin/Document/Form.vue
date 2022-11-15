<template>
    <AppLayout :title="`${document?.id ? 'Edit Document' : 'Create Document'}`" menu="document">
        <template #header>
            <div>
                <Link :href="route('admin.document.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary">
                <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Document
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ document?.id ? document.title : 'Create Document' }}
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
                                {{ document?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ document?.updated_at?.string || '-' }}
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
                                    : document?.id
                                        ? 'Update Data'
                                        : 'Create Data'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20">
                <div class="space-y-6">
                    <TextInput :error="form.errors.title" v-model="form.title" :options="params.title" label="Judul"
                        class="col-span-2" />

                    <TextareaInput :error="form.errors.description" v-model="form.description"
                        :options="params.description" label="Deskripsi" help="Deskripsi Singkat" class="col-span-2" />

                    <FileInput accept=".jpg,.jpeg,.png" :error="form.errors.image" v-model="form.image" type="image" preview-aspect="video"
                        preview-size="full" label="Thumbnail" placeholder="Thumbnail" class="col-span-2" />

                    <FileInput :error="form.errors.file" v-model="form.file" type="file" label="File Dokumen"
                        placeholder="File" class="col-span-2" />



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


import { watch } from 'vue';

const props = defineProps({
    document: Object,
    params: [Object, Array],
});

const form = useForm({
    title: props.document?.title || null,
    description: props.document?.description || null,
    image: props.document?.image || null,
    file: props.document?.file || null,


});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            image: data.image?.object,
            file: data.file?.object,
            _method: props.document?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.document.${props.document?.id ? 'update' : 'store'}`, {
                document: props.document,
            })
        );
</script>
