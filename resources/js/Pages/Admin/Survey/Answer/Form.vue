<template>
    <AppLayout :title="`${answer?.id ? 'Edit Answer' : 'Create Answer'}`" menu="survey.answer">
        <template #header>
            <div>
                <Link
                    :href="route('kedeka::admin.survey.answer.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Survey Answers
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ answer?.id ? answer.name : 'Create Answer' }}
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
                                {{ answer?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ answer?.updated_at?.string || '-' }}
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
                                : answer?.id
                                ? 'Update Answer'
                                : 'Create Answer'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="grid grid-cols-2 gap-6">
                    <SelectInput
                        :error="form.errors.question"
                        v-model="form.question"
                        label="Question"
                        :options="params.questions"
                        class="col-span-2"
                    />
                    <SelectInput
                        :error="form.errors.answer"
                        v-model="form.answer"
                        label="Answer"
                        :options="answerOptions"
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

import find from 'lodash.find';

const props = defineProps({
    answer: Object,
    params: [Object, Array],
});

const form = useForm({
    question: props.answer?.question || null,
    answer: props.answer?.answer || null,
});

const answerOptions = computed(
    () => find(props.params.questions, { id: form.question })?.options || []
);

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            _method: props.answer?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`kedeka::admin.survey.answer.${props.answer?.id ? 'update' : 'store'}`, {
                answer: props.answer,
            })
        );
</script>
