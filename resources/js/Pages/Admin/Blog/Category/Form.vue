<template>
    <AppLayout
        :title="`${category?.id ? 'Edit Category' : 'Create Category'}`"
        menu="blog.category"
    >
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.blog.category.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Blog Categories
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ category?.id ? category.name : 'Create Category' }}
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
                                {{ category?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ category?.updated_at?.string || '-' }}
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
                                : category?.id
                                ? 'Update Category'
                                : 'Create Category'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="grid grid-cols-2 gap-6">
                    <TextInput :error="form.errors.name" v-model="form.name" label="Name" />
                    <TextInput :error="form.errors.slug" v-model="form.slug" label="Slug" />
                    <TextareaInput
                        :error="form.errors.description"
                        v-model="form.description"
                        label="Description"
                        class="col-span-2"
                    />
                    <CheckboxInput
                        :error="form.errors.is_visible"
                        :checked="form.is_visible"
                        @update:checked="form.is_visible = $event"
                        label="Visible to public"
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
import TextareaInput from '@/Shared/TextareaInput.vue';
import CheckboxInput from '@/Shared/CheckboxInput.vue';
import { watch } from 'vue';

const props = defineProps({
    category: Object,
    params: [Object, Array],
});

const form = useForm({
    name: props.category?.name || null,
    slug: props.category?.slug || null,
    description: props.category?.description || null,
    is_visible: props.category?.is_visible || false,
});

watch(
    () => form.name,
    (name) =>
        (form.slug = name
            .toLowerCase()
            .replace(/ /g, '-')
            .replace(/[-]+/g, '-')
            .replace(/[^\w-]+/g, ''))
);

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            _method: props.category?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`kedeka::admin.blog.category.${props.category?.id ? 'update' : 'store'}`, {
                category: props.category,
            })
        );
</script>
