<template>
    <div
        class="px-6 py-4 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
    >
        <h3 class="text-lg font-medium">Social Media</h3>
        <div class="grid grid-cols-2 gap-6 mt-6">
            <TextInput
                :error="form.errors?.facebook?.label"
                v-model="form.facebook.label"
                label="Facebook (Label)"
                placeholder="Username"
            />
            <TextInput
                :error="form.errors?.facebook?.link"
                v-model="form.facebook.link"
                label="Facebook (Link)"
                placeholder="https://facebook.com/username"
            />
            <TextInput
                :error="form.errors?.instagram?.label"
                v-model="form.instagram.label"
                label="Instagram (Label)"
                placeholder="Username"
            />
            <TextInput
                :error="form.errors?.instagram?.link"
                v-model="form.instagram.link"
                label="Instagram (Link)"
                placeholder="https://instagram.com/username"
            />
            <TextInput
                :error="form.errors?.twitter?.label"
                v-model="form.twitter.label"
                label="Twitter (Label)"
                placeholder="Username"
            />
            <TextInput
                :error="form.errors?.twitter?.link"
                v-model="form.twitter.link"
                label="Twitter (Link)"
                placeholder="https://twitter.com/username"
            />
            <TextInput
                :error="form.errors?.youtube?.label"
                v-model="form.youtube.label"
                label="Youtube (Label)"
                placeholder="Channel"
            />
            <TextInput
                :error="form.errors?.youtube?.link"
                v-model="form.youtube.link"
                label="Youtube (Link)"
                placeholder="https://youtube.com/ch/username"
            />
        </div>
        <div class="mt-6 text-right">
            <ButtonPrimary @click="submit" :loading="form.processing" size="md" class="text-sm">{{
                form.processing ? 'Processing...' : 'Update Social Media'
            }}</ButtonPrimary>
        </div>
    </div>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/inertia-vue3';

import ButtonPrimary from '@/Shared/ButtonPrimary.vue';
import TextInput from '@/Shared/TextInput.vue';

const props = defineProps({
    params: Object,
});

const form = useForm({
    facebook: {
        label: props.params?.facebook.label || '',
        link: props.params?.facebook.link || '',
    },
    instagram: {
        label: props.params?.instagram.label || '',
        link: props.params?.instagram.link || '',
    },
    twitter: {
        label: props.params?.twitter.label || '',
        link: props.params?.twitter.link || '',
    },
    youtube: {
        label: props.params?.youtube.label || '',
        link: props.params?.youtube.link || '',
    },
});

const submit = () =>
    form
        .transform((data) => ({
            'social-media': data,
        }))
        .put(route('kedeka::admin.setting.save'));
</script>
