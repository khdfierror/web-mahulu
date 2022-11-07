<template>
    <div
        class="px-6 py-4 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
    >
        <h3 class="text-lg font-medium">Site</h3>
        <div class="grid grid-cols-2 gap-6 mt-6">
            <TextInput
                :error="form.errors?.title"
                v-model="form.title"
                label="Title"
                class="col-span-2"
            />
            <TextareaInput
                :error="form.errors?.description"
                v-model="form.description"
                label="Description"
                class="col-span-2"
                :rows="2"
            />
            <TextareaInput
                :error="form.errors?.footer"
                v-model="form.footer"
                label="Footer"
                class="col-span-2"
                :rows="2"
            />
        </div>
        <div class="mt-6 text-right">
            <ButtonPrimary @click="submit" :loading="form.processing" size="md" class="text-sm">{{
                form.processing ? 'Processing...' : 'Update Footer'
            }}</ButtonPrimary>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

import ButtonPrimary from '@/Shared/ButtonPrimary.vue';
import TextareaInput from '@/Shared/TextareaInput.vue';

const props = defineProps({
    params: Object,
});

const form = useForm({
    title: props.params?.title || '',
    description: props.params?.description || '',
    footer: props.params?.footer || '',
});

const submit = () =>
    form
        .transform((data) => ({
            site: {
                ...data,
            },
        }))
        .put(route('kedeka::admin.setting.save'));
</script>
