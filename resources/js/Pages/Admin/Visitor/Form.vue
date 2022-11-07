<template>
    <AppLayout :title="`${visitor?.id ? 'Edit Visitor' : 'Create Visitor'}`" menu="visitor">
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.visitor.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Visitors
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ visitor?.id ? visitor.ip : 'Create Visitor' }}
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
                                {{ visitor?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ visitor?.updated_at?.string || '-' }}
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
                                : visitor?.id
                                ? 'Update Visitor'
                                : 'Create Visitor'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="space-y-6">
                    <TextInput :error="form.errors.ip" v-model="form.ip" label="IP" />
                    <TextInput :error="form.errors.device" v-model="form.device" label="Device" />
                    <TextInput :error="form.errors.agent" v-model="form.agent" label="Agent" />
                    <TextareaInput
                        :error="form.errors.location"
                        v-model="form.location"
                        label="Location"
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

import TextareaInput from '@/Shared/TextareaInput.vue';
import TextInput from '@/Shared/TextInput.vue';
import TiptapEditor from '@/Shared/TiptapEditor.vue';
import { watch } from 'vue';

const props = defineProps({
    visitor: Object,
    params: [Object, Array],
});

const form = useForm({
    name: props.visitor?.name || null,
    phone: props.visitor?.phone || null,
    message: props.visitor?.message || null,
});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            _method: props.visitor?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`kedeka::admin.visitor.${props.visitor?.id ? 'update' : 'store'}`, {
                visitor: props.visitor,
            })
        );
</script>
