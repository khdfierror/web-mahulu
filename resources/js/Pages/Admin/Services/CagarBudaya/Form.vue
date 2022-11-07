<template>
    <AppLayout
        :title="`${cagar_budaya?.id ? 'Edit Cagar Budaya' : 'Create Cagar Budaya'}`"
        menu="services.cagar-budaya"
    >
        <template #header>
            <div>
                <Link
                    :href="route('admin.services.cagar-budaya.index')"
                    class="inline-flex items-center text-sm text-brand-secondary hover:text-brand-primary"
                >
                    <icon-heroicons-outline-chevron-left class="w-5 h-5 mr-3" />Cagar Budaya
                </Link>
            </div>
            <div class="flex items-center">
                <div class="flex-1">
                    <h2 class="text-2xl font-medium text-brand-dark dark:text-white">
                        {{ cagar_budaya?.id ? cagar_budaya.title : 'Create Cagar Budaya' }}
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
                                {{ cagar_budaya?.created_at?.string || '-' }}
                            </div>
                        </div>
                        <div>
                            <div class="tracking-wider form-label">Last modified at</div>
                            <div class="text-sm text-brand-secondary">
                                {{ cagar_budaya?.updated_at?.string || '-' }}
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
                                : cagar_budaya?.id
                                ? 'Update Cagar Budaya'
                                : 'Create Cagar Budaya'
                        }}
                    </ButtonPrimary>
                </div>
            </div>
            <div
                class="bg-white lg:col-span-4 sm:rounded-lg ring-1 p-6 ring-brand-secondary/[0.05] dark:ring-brand-secondary/30 dark:bg-brand-secondary/20"
            >
                <div class="space-y-6 grid grid-cols-2 gap-x-4">
                    <TextInput
                        :error="form.errors.name"
                        v-model="form.name"
                        label="Nama Cagar Budaya"
                        class="col-span-2"
                    />
                    <TextInput
                        :error="form.errors.cp_name"
                        v-model="form.cp_name"
                        label="Nama Contact Person"
                        class="col-span-2"
                    />
                    <TextInput
                        :error="form.errors.cp_phone"
                        v-model="form.cp_phone"
                        label="No Telp Contact Person"
                    />
                    <TextInput
                        :error="form.errors.cp_email"
                        v-model="form.cp_email"
                        label="Email Contact Person"
                    />

                    <TextInput
                        :error="form.errors.no_inventaris"
                        v-model="form.no_inventaris"
                        label="No Inventaris"
                    />
                    <TextInput
                        :error="form.errors.periode"
                        v-model="form.periode"
                        label="Periode"
                    />
                    <TextInput :error="form.errors.bahan" v-model="form.bahan" label="Bahan" />
                    <TextInput :error="form.errors.ukuran" v-model="form.ukuran" label="Ukuran" />
                    <TextareaInput
                        :error="form.errors.keterangan"
                        v-model="form.keterangan"
                        label="Keterangan"
                        class="col-span-2"
                    />

                    <SelectInput
                        :error="form.errors.province_code"
                        v-model="form.province_code"
                        :options="params.provinces"
                        label="Provinsi"
                    />
                    <SelectInput
                        :error="form.errors.city_code"
                        v-model="form.city_code"
                        :options="cities"
                        label="Kabupaten / Kota"
                    />
                    <SelectInput
                        :error="form.errors.district_code"
                        v-model="form.district_code"
                        :options="districts"
                        label="Kecamatan"
                    />
                    <SelectInput
                        :error="form.errors.ward_code"
                        v-model="form.ward_code"
                        :options="wards"
                        label="Kelurahan"
                    />
                    <TextInput :error="form.errors.lat" v-model="form.lat" label="Latitude" />
                    <TextInput :error="form.errors.lng" v-model="form.lng" label="longitude" />
                    <TextareaInput
                        :error="form.errors.address"
                        v-model="form.address"
                        label="Alamat"
                        class="col-span-2"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonPrimary from '@/Shared/ButtonPrimary.vue';

import TextInput from '@/Shared/TextInput.vue';
import SelectInput from '@/Shared/SelectInput.vue';
import TextareaInput from '@/Shared/TextareaInput.vue';

import { watch } from 'vue';

const props = defineProps({
    cagar_budaya: Object,
    params: [Object, Array],
    cities: [Object, Array],
    districts: [Object, Array],
    wards: [Object, Array],
});

const form = useForm({
    name: props.cagar_budaya?.name || null,
    cp_name: props.cagar_budaya?.cp_name || null,
    cp_phone: props.cagar_budaya?.cp_phone || null,
    cp_email: props.cagar_budaya?.cp_email || null,

    no_inventaris: props.cagar_budaya?.no_inventaris || null,
    periode: props.cagar_budaya?.periode || null,
    bahan: props.cagar_budaya?.bahan || null,
    ukuran: props.cagar_budaya?.ukuran || null,
    keterangan: props.cagar_budaya?.keterangan || null,

    address: props.cagar_budaya?.address || null,
    province_code: props.cagar_budaya?.province_code || null,
    city_code: props.cagar_budaya?.city_code || null,
    district_code: props.cagar_budaya?.district_code || null,
    ward_code: props.cagar_budaya?.ward_code || null,
    lat: props.cagar_budaya?.lat || null,
    lng: props.cagar_budaya?.lng || null,
});

const partialReset = (option) => {
    if (option == 'province') {
        form.city_code = null;
        form.district_code = null;
        form.ward_code = null;
    } else if (option == 'city') {
        form.district_code = null;
        form.ward_code = null;
    } else {
        form.ward_code = null;
    }
};

watch(
    () => form.province_code,
    () => {
        Inertia.visit(
            route(`admin.services.cagar-budaya.${props.cagar_budaya?.id ? 'edit' : 'create'}`, {
                cagar_budaya: props.cagar_budaya.id,
                province: form.province_code,
            }),
            {
                preserveScroll: true,
                preserveState: true,
                only: ['cities'],
                onSuccess: () => partialReset('province'),
            }
        );
    }
);

watch(
    () => form.city_code,
    () => {
        if (form.city_code !== null) {
            Inertia.visit(
                route(`admin.services.cagar-budaya.${props.cagar_budaya?.id ? 'edit' : 'create'}`, {
                    cagar_budaya: props.cagar_budaya.id,
                    city: form.city_code,
                }),
                {
                    preserveScroll: true,
                    preserveState: true,
                    only: ['districts'],
                    onSuccess: () => partialReset('district'),
                }
            );
        }
    }
);

watch(
    () => form.district_code,
    () => {
        if (form.district_code !== null) {
            Inertia.visit(
                route(`admin.services.cagar-budaya.${props.cagar_budaya?.id ? 'edit' : 'create'}`, {
                    cagar_budaya: props.cagar_budaya.id,
                    district: form.district_code,
                }),
                {
                    preserveScroll: true,
                    preserveState: true,
                    only: ['wards'],
                }
            );
        }
    }
);

const submit = () =>
    form
        .transform((data) => ({
            ...data,
            _method: props.cagar_budaya?.id ? 'PUT' : 'POST',
        }))
        .post(
            route(`admin.services.cagar-budaya.${props.cagar_budaya?.id ? 'update' : 'store'}`, {
                cagar_budaya: props.cagar_budaya,
            })
        );
</script>
