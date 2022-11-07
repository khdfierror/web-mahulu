<template>
    <div
        class="px-6 py-4 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
    >
        <h3 class="text-lg font-medium">Contact</h3>
        <div class="grid grid-cols-2 gap-6 mt-6">
            <TextInput
                v-for="(label, key) in options"
                :error="form.errors?.[key]"
                v-model="form[key]"
                :label="label"
            />
        </div>
        <div class="mt-6 text-right">
            <ButtonPrimary @click="submit" :loading="form.processing" size="md" class="text-sm">{{
                form.processing ? 'Processing...' : 'Update Contact'
            }}</ButtonPrimary>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

import ButtonPrimary from '@/Shared/ButtonPrimary.vue';
import TextInput from '@/Shared/TextInput.vue';

const props = defineProps({
    params: Object,
    options: [Object, Array],
});

const form = useForm();

onMounted(() => {
    Object.keys(props.options).forEach((key) => {
        form[key] = props.params[key];
    });
});

const submit = () => {
    const data = {};
    Object.keys(props.options).forEach((key) => {
        data[key] = form[key];
    });

    form.transform(() => ({
        contact: data,
    })).put(route('kedeka::admin.setting.save'));
};
</script>
