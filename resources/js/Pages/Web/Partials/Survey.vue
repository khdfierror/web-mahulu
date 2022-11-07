<script setup>
const survey = computed(() => usePage().props.value?.survey);
const form = useForm();

const submit = (answer) =>
    form
        .transform(() => ({
            question: survey.value?.id,
            answer,
        }))
        .post(route('kedeka::survey.receipt-answer'), {
            preserveState: true,
            preserveScroll: true,
        });
</script>

<template>
    <div class="px-12 text-white bg-brand-dark sm:px-14 lg:px-16">
        <div
            :class="[
                'grid max-w-6xl mx-auto md:grid-cols-3 md:gap-8',
                survey?.submitted ? 'py-10' : 'py-20',
            ]"
        >
            <div class="flex flex-col justify-center">
                <h3 class="text-4xl">Survei Kepuasan</h3>
            </div>
            <div class="flex flex-col justify-center">
                <div v-html="survey?.question" />
                <template v-for="error in form.errors">
                    <div class="form-error">{{ error }}</div>
                </template>
            </div>
            <div class="flex items-center">
                <div class="flex space-x-5" v-if="!survey?.submitted">
                    <button
                        type="button"
                        @click="submit(answer.value)"
                        v-for="answer in survey?.options"
                        :class="[
                            'px-5 py-2 transition-all border whitespace-nowrap',
                            answer.feature
                                ? 'bg-white text-brand-dark hover:bg-brand-primary hover:text-white'
                                : 'border-white hover:bg-white/10',
                        ]"
                    >
                        {{ answer.value }}
                    </button>
                </div>
                <div v-else class="flex-1">
                    <ul class="space-y-3">
                        <li v-for="answer in survey?.options">
                            <div class="flex w-full space-x-5">
                                <div>
                                    {{ answer.value }}
                                </div>
                                <div class="text-slate-500">{{ n(answer.percent) }}%</div>
                            </div>
                            <div
                                class="w-full h-1 mt-1 bg-white"
                                :style="`width: ${answer.percent}%`"
                            ></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
