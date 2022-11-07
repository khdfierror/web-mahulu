<template>
    <div
        class="px-6 py-4 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
    >
        <h3 class="text-lg font-medium">Sekilas Tentang Kami</h3>
        <div class="grid grid-cols-6 gap-4 mt-6">
            <div class="col-span-2">
                <FileInput
                    label="Gambar"
                    type="image"
                    v-model="form.photo"
                    preview-size="full"
                    preview-aspect="auto"
                />
            </div>

            <div class="col-span-4 space-y-4">
                <TextInput :error="form.errors.title" v-model="form.title" label="Judul" />
                <TextareaInput
                    :error="form.errors.description"
                    v-model="form.description"
                    label="Deskripsi"
                />
                <TextInput :error="form.errors.link" v-model="form.link" label="Link" />
            </div>
        </div>
        <div class="mt-6 text-right">
            <ButtonPrimary @click="submit" :loading="form.processing" size="md" class="text-sm">{{
                form.processing ? 'Processing...' : 'Update Sekilas Tentang Kami'
            }}</ButtonPrimary>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

import ButtonPrimary from '@/Shared/ButtonPrimary.vue';
import TextareaInput from '@/Shared/TextareaInput.vue';
import FileInput from '@/Shared/FileInput.vue';
import TextInput from '@/Shared/TextInput.vue';

const props = defineProps({
    params: Object,
});

const photo = props.params?.photo ? JSON.parse(props.params?.photo) : null;

const form = useForm({
    photo: photo || '',
    description: props.params?.description || '',
    title: props.params?.title || '',
    link: props.params?.link || '',
});

const submit = () =>
    form
        .transform((data) => ({
            about: {
                photo: data.photo?.object,
                description: data.description,
                title: data.title,
                link: data.link,
            },
            _method: 'PUT',
        }))
        .post(route('kedeka::admin.setting.save'));
</script>
