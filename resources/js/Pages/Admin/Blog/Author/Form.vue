<template>
    <AppLayout :title="`${author?.id ? 'Edit Author' : 'Create Author'}`" menu="blog.author">
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.blog.author.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Blog Authors
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ author?.id ? author.name : 'Create Author' }}
                    </h2>
                </div>
                <div></div>
            </div>
        </template>

        <div class="mt-6 grid lg:grid-cols-6 lg:grid-flow-row-dense gap-6">
            <div class="lg:col-span-2 lg:col-start-5">
                <div
                    class="bg-white sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20 dark:ring-brand-secondary/30"
                >
                    <div class="space-y-6">
                        <div>
                            <div class="form-label tracking-wider">Created at</div>
                            <div class="text-brand-secondary text-sm">
                                {{ author?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="form-label tracking-wider">Last modified at</div>
                            <div class="text-brand-secondary text-sm">
                                {{ author?.updated_at?.string || '-' }}
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
                                : author?.id
                                ? 'Update Author'
                                : 'Create Author'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="grid grid-cols-2 gap-6">
                    <TextInput :error="form.errors.name" v-model="form.name" label="Name" />
                    <TextInput :error="form.errors.email" v-model="form.email" label="Email" />
                    <TiptapEditor
                        :error="form.errors.bio"
                        v-model="form.bio"
                        label="Bio"
                        class="col-span-2"
                    />
                    <TextInput
                        :error="form.errors.instagram"
                        v-model="form.instagram"
                        label="Instagram"
                    />
                    <TextInput
                        :error="form.errors.twitter"
                        v-model="form.twitter"
                        label="Twitter"
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
    author: Object,
    params: [Object, Array],
});

const form = useForm({
    name: props.author?.name || null,
    email: props.author?.email || null,
    bio: props.author?.bio || null,
    instagram: props.author?.instagram || null,
    twitter: props.author?.twitter || null,
});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            _method: props.author?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`kedeka::admin.blog.author.${props.author?.id ? 'update' : 'store'}`, {
                author: props.author,
            })
        );
</script>
