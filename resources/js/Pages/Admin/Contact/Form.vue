<template>
    <AppLayout :title="`${contact?.id ? 'Edit Contact' : 'Create Contact'}`" menu="contact">
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.contact.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Contacts
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ contact?.id ? contact.name : 'Create Contact' }}
                    </h2>
                </div>
                <div></div>
            </div>
        </template>

        <div class="grid gap-6 mt-6 lg:grid-cols-6 lg:grid-flow-row-dense">
            <div class="lg:col-span-2 lg:col-start-5">
                <div
                    class="bg-white sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20 dark:ring-brand-secondary/30 dark:ring-brand-secondary/30"
                >
                    <div class="space-y-6">
                        <div>
                            <div class="tracking-wider form-label">Created at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ contact?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ contact?.updated_at?.string || '-' }}
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
                                : contact?.id
                                ? 'Update Contact'
                                : 'Create Contact'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="space-y-6">
                    <TextInput :error="form.errors.name" v-model="form.name" label="Name" />
                    <TextInput :error="form.errors.phone" v-model="form.phone" label="Phone" />
                    <TiptapEditor
                        :error="form.errors.message"
                        v-model="form.message"
                        label="Message"
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
    contact: Object,
    params: [Object, Array],
});

const form = useForm({
    name: props.contact?.name || null,
    phone: props.contact?.phone || null,
    message: props.contact?.message || null,
});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            _method: props.contact?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`kedeka::admin.contact.${props.contact?.id ? 'update' : 'store'}`, {
                contact: props.contact,
            })
        );
</script>
