<template>
    <AppLayout
        :title="`${navigation?.id ? 'Edit Navigation' : 'Create Navigation'}`"
        menu="navigation"
    >
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.navigation.navigation.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />News Navigations
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ navigation?.id ? navigation.name : 'Create Navigation' }}
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
                                {{ navigation?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ navigation?.updated_at?.string || '-' }}
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
                                : navigation?.id
                                ? 'Update Navigation'
                                : 'Create Navigation'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="grid gap-6 lg:grid-cols-2">
                    <TextInput
                        :error="form.errors.name"
                        v-model="form.name"
                        label="Name"
                        class="lg:col-span-2"
                    />
                    <TextInput
                        :error="form.errors.url"
                        v-model="form.url"
                        label="URL"
                        class="lg:col-span-2"
                    />
                    <SelectInput
                        :error="form.errors.parent"
                        v-model="form.parent"
                        label="Parent"
                        :options="[{ id: null, label: 'No Parent' }, ...params.parents]"
                        class="lg:col-span-2"
                    />
                    <CheckboxInput
                        v-if="parent?.level === 1"
                        :checked="form.is_group"
                        @update:checked="form.is_group = $event"
                        :error="form.errors.is_group"
                        label="Group"
                    />
                    <TextareaInput
                        v-if="form.is_group"
                        :error="form.errors.description"
                        v-model="form.description"
                        label="Description"
                        class="lg:col-span-2"
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
import SelectInput from '@/Shared/SelectInput.vue';
import { watch } from 'vue';
import find from 'lodash.find';

const props = defineProps({
    navigation: Object,
    params: [Object, Array],
});

const form = useForm({
    name: props.navigation?.name || null,
    url: props.navigation?.url || null,
    parent: props.navigation?.parent || null,
    is_group: props.navigation?.is_group || false,
    description: props.navigation?.description || null,
});

const parent = computed(() => find(props.params.parents, { id: form.parent }));

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            description: data.is_group ? data.description : null,
            _method: props.navigation?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(
                `kedeka::admin.navigation.navigation.${props.navigation?.id ? 'update' : 'store'}`,
                { navigation: props.navigation }
            )
        );
</script>
