<template>
    <AppLayout :title="`${news?.id ? 'Edit News' : 'Create News'}`" menu="news">
        <template #header>
            <div>
                <Link :href="route('admin.news.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary">
                <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />News
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ news?.id ? news.title : 'Create News' }}
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
                                {{ news?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ news?.updated_at?.string || '-' }}
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
                                    : news?.id
                                        ? 'Update Data'
                                        : 'Create Data'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20">
                <div class="space-y-6">
                    <TextInput :error="form.errors.title" v-model="form.title" :options="params.title" label="Judul Berita" class="col-span-2"/>

                    <FileInput :error="form.errors.image" v-model="form.image" type="image" preview-aspect="video" preview-size="full"
                        label="Foto Berita" placeholder="Foto" class="col-span-2" />

                    <TiptapEditor :error="form.errors.content" v-model="form.content" label="Content" class="col-span-2" />

                    <DateInput :error="form.errors.date" v-model="form.date" placeholder="Tanggal"
                        label="Tanggal" class="col-span-2"/>

                    <TextareaInput :error="form.errors.location" v-model="form.location" :options="params.location" label="Lokasi"
                        help="Misal: Hotel Aston Samarinda" class="col-span-2"/>
                    
            
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
    news: Object,
    params: [Object, Array],
});

const form = useForm({
    title: props.news?.title || null,
    image: props.news?.image || null,
    content: props.news?.content || null,
    date: props.news?.date || null,
    location: props.news?.location || null,

    
});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            image: data.image?.object,
            _method: props.news?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.news.${props.news?.id ? 'update' : 'store'}`, {
                news: props.news,
            })
        );
</script>
