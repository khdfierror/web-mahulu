<template>
    <div
        class="px-6 py-4 bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20"
    >
        <h3 class="text-lg font-medium">Link Layanan Kami</h3>
        <div class="grid grid-cols-2 gap-6 mt-6">
            <TextInput
                :error="form.errors?.perizinan"
                v-model="form.perizinan"
                label="Perizinan"
                help="link default: services/perizinan"
            />
            <TextInput
                :error="form.errors?.odcb"
                v-model="form.odcb"
                label="Temuan ODCB"
                help="link default: services/temuan"
            />
            <TextInput
                :error="form.errors?.data_cb"
                v-model="form.data_cb"
                label="Permohonan Data CB"
                help="link default: services/permohonan"
            />
            <TextInput
                :error="form.errors?.kasus_cb"
                v-model="form.kasus_cb"
                label="Pelaporan Kasus CB"
                help="link default: services/pelaporan"
            />
            <TextInput
                :error="form.errors?.sp4n_lapor"
                v-model="form.sp4n_lapor"
                label="SP4N Lapor"
                help="link default: https://www.lapor.go.id"
            />
            <TextInput
                :error="form.errors?.gratifikasi"
                v-model="form.gratifikasi"
                label="Lapor Gratifikasi"
                help="link default: services/gratifikasi"
            />
            <TextInput
                :error="form.errors?.wbs"
                v-model="form.wbs"
                label="WBS"
                help="link default: services/wbs"
            />
            <TextInput
                :error="form.errors?.tindak_lanjut"
                v-model="form.tindak_lanjut"
                label="Tindak Lanjut"
                help="link default: services/tindak-lanjut"
            />
        </div>
        <div class="mt-6 text-right">
            <ButtonPrimary @click="submit" :loading="form.processing" size="md" class="text-sm">{{
                form.processing ? 'Processing...' : 'Update Link Layanan Kami'
            }}</ButtonPrimary>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    params: Object,
});

const form = useForm({
    perizinan: props.params?.perizinan || '',
    odcb: props.params?.odcb || '',
    data_cb: props.params?.data_cb || '',
    kasus_cb: props.params?.kasus_cb || '',
    sp4n_lapor: props.params?.sp4n_lapor || '',
    gratifikasi: props.params?.gratifikasi || '',
    wbs: props.params?.wbs || '',
    tindak_lanjut: props.params?.tindak_lanjut || '',
});

const submit = () =>
    form
        .transform((data) => ({
            service: {
                ...data,
            },
        }))
        .put(route('kedeka::admin.setting.save'));
</script>
