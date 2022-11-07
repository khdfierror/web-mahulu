<template>
    <Head title="Log In" />
    <div class="flex flex-col items-center min-h-screen bg-gray-50 pt-6 sm:justify-center sm:pt-0">
        <div class="w-full max-w-sm">
            <Link href="/" class="flex justify-center">
                <Logo alt class="h-16 text-[#4D4D4D] dark:text-white" />
            </Link>
            <div class="mt-3 text-lg text-center text-brand-secondary">
                Log In untuk menggunakan layanan
            </div>
            <form
                @submit.prevent="submit"
                class="relative p-6 mt-5 overflow-hidden bg-white sm:rounded-lg ring-1 ring-brand-secondary/[0.05] dark:bg-brand-secondary/20 rounded-lg"
            >
                <LoadingOverlay :open="form.processing" />
                <div class="space-y-5">
                    <TextInput
                        type="email"
                        label="Email"
                        v-model="form.email"
                        :error="form.errors.email"
                    />
                    <TextInput
                        type="password"
                        label="Password"
                        v-model="form.password"
                        :error="form.errors.password"
                    />
                    <CheckboxInput
                        :checked="form.remember"
                        @update:checked="form.remember = $event"
                        :error="form.errors.remember"
                        label="Ingat saya"
                    />
                </div>
                <div class="flex items-center justify-end mt-5">
                    <div class="mr-5 text-sm text-brand-secondary">
                        <Link
                            :href="route('password.request')"
                            class="underline hover:text-brand-primary"
                            >Lupa Password?</Link
                        >
                    </div>
                    <div>
                        <ButtonPrimary type="submit">Log In</ButtonPrimary>
                    </div>
                </div>
            </form>
            <div class="py-5 text-xs text-center text-brand-secondary/50">
                <div>&copy; 2022 Balai Pelestarian Cagar Budaya Kalimantan.</div>
                <div>All Rights Reserved.</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    email: null,
    password: null,
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        preserveScroll: true,
        onFinish: () => {
            form.processing = false;
        },
    });
};
</script>
