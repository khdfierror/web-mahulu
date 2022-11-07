<template>
    <AppLayout
        :title="`${question?.id ? 'Edit Question' : 'Create Question'}`"
        menu="survey.question"
    >
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.survey.question.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Survey Questions
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ question?.id ? question.question : 'Create Question' }}
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
                                {{ question?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ question?.updated_at?.string || '-' }}
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
                                : question?.id
                                ? 'Update Question'
                                : 'Create Question'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="grid grid-cols-2 gap-6">
                    <TextInput
                        :error="form.errors.question"
                        v-model="form.question"
                        label="Question"
                        class="col-span-2"
                    />
                    <OptionsInput
                        :error="form.errors.options"
                        v-model="form.options"
                        class="col-span-2"
                    />
                    <CheckboxInput
                        :error="form.errors.is_active"
                        :checked="form.is_active"
                        @update:checked="form.is_active = $event"
                        label="Active"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import pluck from 'lodash.pluck';
import OptionsInput from './Partials/OptionsInput.vue';

const props = defineProps({
    question: Object,
    params: [Object, Array],
});

const form = useForm({
    question: props.question?.question || null,
    options: props.question?.options || [],
    is_active: props.question?.is_active || false,
});

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            _method: props.question?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`kedeka::admin.survey.question.${props.question?.id ? 'update' : 'store'}`, {
                question: props.question,
            })
        );
</script>
