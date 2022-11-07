<template>
    <div
        class="px-6 py-4 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
    >
        <h3 class="text-lg font-medium">Data Diri Whistleblower / Pelapor</h3>
        <div class="grid grid-cols-2 gap-6 mt-6">
            <TextInput
                :error="form.errors?.nama"
                v-model="form.nama"
                label="Nama Whistleblower"
                class="col-span-2 lg:col-span-1"
            />
            <TextInput
                :error="form.errors?.nomor_identitas"
                v-model="form.nomor_identitas"
                label="NIP/NIK Whistleblower"
                class="col-span-2 lg:col-span-1"
            />
            <TextInput
                :error="form.errors?.whatsapp"
                v-model="form.whatsapp"
                label="Nomor Whatsapp Whistleblower"
                class="col-span-2 lg:col-span-1"
            />
            <TextInput
                :error="form.errors?.email"
                v-model="form.email"
                label="Email Whistleblower"
                class="col-span-2 lg:col-span-1"
            />
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { watchDebounced } from '@vueuse/core';

import ButtonPrimary from '@/Shared/ButtonPrimary.vue';
import TextareaInput from '@/Shared/TextareaInput.vue';
import SelectInput from '@/Shared/SelectInput.vue';

const emit = defineEmits(['updatePelaporData']);

const props = defineProps({
    params: Object,
    errors: Object,
});
console.log(props.params);
const form = useForm({
    nama: props.params?.nama || null,
    nomor_identitas: props.params?.nomor_identitas || null,
    whatsapp: props.params?.whatsapp || null,
    email: props.params?.email || null,
});

watchDebounced(
    form,
    (value) => {
        console.log(form.image);
        emit('updatePelaporData', {
            nama: form.nama,
            nomor_identitas: form.nomor_identitas,
            whatsapp: form.whatsapp,
            email: form.email,
        });
    },
    { debounce: 300 }
);

watch(
    () => props.errors,
    (errors) => {
        Object.keys(props.errors)
            .filter((err) => err.startsWith('pelapor'))
            .forEach((element) => {
                let field = element.replace('pelapor.', '');
                let errorMessage = props.errors[element].replace('pelapor.', '');
                form.setError(field, errorMessage);
            });
    },
    {
        deep: true,
    }
);
</script>
