<template>
    <div>
        <LoadingOverlay :open="form.processing" />
        <h3 class="font-bold">Kirim Pesan</h3>
        <div class="flex flex-col mt-6 space-y-4">
            <TextInput :error="form.errors.name" v-model="form.name" placeholder="Nama" />
            <TextInput
                :error="form.errors.phone"
                v-model="form.phone"
                placeholder="Nomor WhatsApp"
            />
            <TextareaInput
                :error="form.errors.message"
                v-model="form.message"
                placeholder="Pesan"
            />
            <OtpInput
                v-show="otpTimeout.start ? true : false"
                v-model="form.otp"
                :otp-timeout="otpTimeout"
                :error="form.errors?.otp"
                class="mt-4"
                @submit="
                    (otp) => {
                        form.otp = otp;
                    }
                "
                @need-request-otp="needRequestOtp = $event"
            />
            <div class="form-error" v-if="otpTimeout.start ? false : true">
                {{ form.errors?.otp }}
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            <button
                type="button"
                v-show="!otpTimeout.end"
                @click="requestOtp"
                class="text-brand-primary"
            >
                Minta Kode OTP
            </button>
            <button
                type="submit"
                @click="submit"
                class="px-4 py-2 ml-auto text-white bg-brand-primary hover:bg-brand-primary/80"
            >
                Kirim
            </button>
        </div>
    </div>
</template>

<script setup>
import TextInput from './Form/TextInput.vue';
import TextareaInput from './Form/TextareaInput.vue';
import OtpInput from './Form/OtpInput.vue';

const form = useForm({
    name: null,
    phone: null,
    message: null,
    otp: null,
});

const submit = () =>
    form
        .transform((data) => ({
            name: data.name,
            phone: data.phone,
            message: data.message,
            otp: needRequestOtp.value ? undefined : data.otp,
            timestamp: new Date(),
        }))
        .post(route('send-message'), {
            preserveScroll: true,
            onSuccess: () => {
                otpTimeoutReset();
                needRequestOtp.value = false;
                form.name = null;
                form.phone = null;
                form.message = null;
                form.otp = null;
            },
        });

const needRequestOtp = ref(false);
const time = 60;

const requestOtp = () =>
    form
        .transform((data) => ({
            phone: data.phone,
            otp: data.otp,
            timestamp: new Date(),
        }))
        .post(route('request-otp'), {
            preserveScroll: true,
            preserveState: true,
            onBefore: () => {
                if (needRequestOtp.value) {
                    otpTimeoutReset();
                    needRequestOtp.value = false;
                }
            },
            onSuccess: (success) => {
                otpTimeoutReset();
                otpTimeout.value.start = new Date();
                otpTimeout.value.end = new Date();
                otpTimeout.value.end = otpTimeout.value.end.setSeconds(
                    otpTimeout.value.start.getSeconds() + time
                );
                needRequestOtp.value = false;
            },
        });

const otpTimeout = ref({
    start: null,
    end: null,
    timer: null,
});

const otpTimeoutReset = () =>
    (otpTimeout.value = {
        start: null,
        end: null,
        timer: null,
    });

watch(
    () => needRequestOtp.value,
    (value) => {
        if (value) {
            otpTimeoutReset();
        }
    }
);
</script>
