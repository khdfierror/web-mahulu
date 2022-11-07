<script setup>
import dayjs from 'dayjs';

const props = defineProps({
    otpTimeout: Object,
    error: String,
});

const emit = defineEmits(['submit', 'needRequestOtp']);

const otpStart = ref(props?.otp?.start);
const otpEnd = ref(props?.otp?.end);
const otpTimer = ref(props?.otp?.timer);

const logged = ref(false);

const otpInputs = reactive({
    input1: null,
    input2: null,
    input3: null,
    input4: null,
});

const submit = () => emit('submit', Object.values(otpInputs)?.join(''));

const focusInput = (index) => {
    const element = document.getElementById(`otp-input${index}`);

    element?.focus();
    element?.select();
};

const isCodeNumeric = (charCode) => {
    // numeric keys and numpad keys
    return (charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105);
};

const onFocus = (key, index) => {
    document.getElementById(`otp-input${index}`)?.select();
};

const onKeydown = (key, index, keyEvent) => {
    const charCode = keyEvent.which ? keyEvent.which : keyEvent.keyCode;

    if (!isCodeNumeric(charCode) && charCode !== 8) {
        // on press backspace
        keyEvent.preventDefault();
    }
};

const onKeyup = (key, index, keyEvent) => {
    const charCode = keyEvent.which ? keyEvent.which : keyEvent.keyCode;

    if (charCode === 8) {
        // on press backspace
        if (!otpInputs?.[key]) focusInput(index - 1);
    }
};

const onInput = (key, index, event) => {
    otpInputs[key] = event.target.value;
    if (otpInputs[key]) {
        focusInput(index + 1);

        if (index === 3) submit();
    }
};

const resetInput = () => {
    otpInputs.input1 = null;
    otpInputs.input2 = null;
    otpInputs.input3 = null;
    otpInputs.input4 = null;
};

const resetOtp = () => {
    otpStart.value = null;
    otpEnd.value = null;
    otpTimer.value = null;
};

watch(
    () => props.otpTimeout,
    (value) => {
        const { start, end, timer } = value;

        if (!start && !end && !timer) return;

        otpStart.value = dayjs(start);
        otpEnd.value = dayjs(end);
        otpTimer.value = parseInt(timer);

        if (otpEnd.value - otpStart.value > 0) {
            const { pause } = useIntervalFn(() => {
                otpStart.value = otpStart.value.add(1, 'second');
                otpTimer.value = otpEnd.value - otpStart.value;
                if (otpTimer.value <= 0 || logged.value) {
                    pause();
                    emit('needRequestOtp', true);
                    otpTimer.value = 0;
                }
            }, 1000);

            nextTick(() => setTimeout(() => focusInput(0), 100));
        } else {
            resetOtp();
        }
    },
    {
        deep: true,
    }
);
</script>

<template>
    <div class="field-wrapper">
        <label class="text-brand-gray"> Kode OTP </label>
        <div class="flex justify-between mt-1 space-x-4 form-wrapper">
            <input
                v-for="(value, key, index) in otpInputs"
                :id="`otp-input${index}`"
                :key="key"
                :value="value"
                :autocomplete="index === 0 ? 'one-time-code' : undefined"
                inputtype="numeric"
                pattern="[0-9]*"
                type="tel"
                class="block w-full max-w-[3rem] text-center border focus:outline-none focus:ring-0 px-3 py-2 border-brand-gray focus:border-brand-primary"
                maxlength="1"
                @focus="onFocus(key, index)"
                @keydown="onKeydown(key, index, $event)"
                @input="onInput(key, index, $event)"
                @keyup="onKeyup(key, index, $event)"
            />
        </div>
        <div v-if="error" class="mt-4 font-medium text-center text-red-500">
            {{ error }}
        </div>
        <div v-if="otpTimer && !logged" class="mt-4 text-center">
            <span class="font-medium">{{ Math.floor((otpTimer / 1000 / 60) << 0) || 0 }}</span>
            <span class="text-gray-500"> menit </span>
            <span class="font-medium"> {{ Math.floor((otpTimer / 1000) % 60) || 0 }}</span>
            <span class="text-gray-500"> detik </span>
        </div>
        <div v-if="otpEnd && otpTimer <= 0" class="mt-4 text-center text-gray-600">
            Waktu OTP telah habis silakan request kembali
        </div>
    </div>
</template>
