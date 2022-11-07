<template>
    <AppLayout :title="`${slider?.id ? 'Edit Slider' : 'Create Slider'}`" menu="slider">
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.slider.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Sliders
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ slider?.id ? slider.title : 'Create Slider' }}
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
                            <div class="text-sm text-brand-secondary/50 dark:text-brand-secondary">
                                {{ slider?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary/50 dark:text-brand-secondary">
                                {{ slider?.updated_at?.string || '-' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20 dark:ring-brand-secondary/30 mt-8"
                >
                    <div class="space-y-6">
                        <TextInput :error="form.errors.order" v-model="form.order" label="Order" />
                        <TextInput :error="form.errors.link" v-model="form.link" label="Link" />
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
                                : slider?.id
                                ? 'Update Slider'
                                : 'Create Slider'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="grid gap-6 lg:grid-cols-2">
                    <TextInput
                        :error="form.errors.title"
                        v-model="form.title"
                        label="Title"
                        class="col-span-2"
                    />
                    <TextareaInput
                        :error="form.errors.description"
                        v-model="form.description"
                        label="Description"
                        class="col-span-2"
                    />
                    <FileInput
                        :error="form.errors.image"
                        v-model="form.image"
                        type="image"
                        preview-aspect="video"
                        preview-size="full"
                        label="Image"
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
import FileInput from '@/Shared/FileInput.vue';

const props = defineProps({
    slider: Object,
    params: [Object, Array],
});

const form = useForm({
    order: props.slider?.order || null,
    title: props.slider?.title || null,
    description: props.slider?.description || null,
    link: props.slider?.link || null,
    image: props.slider?.image || null,
});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            image: data.image?.object,
            _method: props.slider?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`kedeka::admin.slider.${props.slider?.id ? 'update' : 'store'}`, {
                slider: props.slider,
            })
        );
</script>
