<template>
    <AppLayout :title="`${post?.id ? 'Edit Post' : 'Create Post'}`" menu="blog.post">
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.blog.post.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Blog Posts
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ post?.id ? post.title : 'Create Post' }}
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
                                {{ post?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ post?.updated_at?.string || '-' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20 dark:ring-brand-secondary/30 mt-8"
                >
                    <div class="space-y-6">
                        <SelectInput
                            :error="form.errors.author"
                            v-model="form.author"
                            label="Author"
                            :options="params.authors"
                        />
                        <SelectInput
                            :error="form.errors.category"
                            v-model="form.category"
                            label="Category"
                            :options="params.categories"
                        />
                        <DateInput
                            :error="form.errors.published_at"
                            v-model="form.published_at"
                            placeholder="Published At"
                            label="Published Date"
                        />
                        <SelectAutocomplete
                            :error="form.errors.tags"
                            force-upper-case
                            v-model="form.tags"
                            label="Tags"
                            :options="params.tags || []"
                        />
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
                                : post?.id
                                ? 'Update Post'
                                : 'Create Post'
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
                    <TextInput
                        :error="form.errors.slug"
                        v-model="form.slug"
                        label="Slug"
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
                    <TiptapEditor
                        :error="form.errors.content"
                        v-model="form.content"
                        label="Content"
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

import DateInput from '@/Shared/DateInput.vue';
import TextInput from '@/Shared/TextInput.vue';
import FileInput from '@/Shared/FileInput.vue';
import TiptapEditor from '@/Shared/TiptapEditor.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import SelectMultipleInput from '@/Shared/SelectMultipleInput.vue';
import SelectAutocomplete from '@/Shared/SelectAutocomplete.vue';
import { watch } from 'vue';

const props = defineProps({
    post: Object,
    params: [Object, Array],
});

const form = useForm({
    title: props.post?.title || null,
    image: props.post?.image || null,
    slug: props.post?.slug || null,
    content: props.post?.content || null,
    published_at: props.post?.published_at || null,
    author: props.post?.author?.id || null,
    category: props.post?.category?.id || null,
    tags: props.post?.tags || [],
});

watch(
    () => form.title,
    (title) =>
        (form.slug = title
            .toLowerCase()
            .replace(/ /g, '-')
            .replace(/[-]+/g, '-')
            .replace(/[^\w-]+/g, ''))
);

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            image: data.image?.object,
            _method: props.post?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`kedeka::admin.blog.post.${props.post?.id ? 'update' : 'store'}`, {
                post: props.post,
            })
        );
</script>
