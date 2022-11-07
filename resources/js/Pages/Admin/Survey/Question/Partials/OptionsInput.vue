<template>
    <div class="field-wrapper">
        <div class="form-label">Options</div>
        <div class="form-wrapper">
            <div class="space-y-4">
                <template v-for="(item, index) in form">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <TextInput
                                v-model="item.value"
                                :error="$page.props.errors?.[`options.${index}.label`]"
                            />
                        </div>
                        <div class="flex items-center pl-3">
                            <CheckboxInput
                                :checked="item.feature"
                                @update:checked="item.feature = $event"
                                label="Featured"
                                :error="$page.props.errors?.[`options.${index}.feature`]"
                            />
                        </div>
                        <div class="flex items-center pl-3" v-if="form.length > 1">
                            <button
                                @click="remove(item, index)"
                                type="button"
                                tabindex="-1"
                                class="text-rose-500 hover:text-rose-400 active:text-rose-600"
                            >
                                <icon-heroicons-outline-x-circle class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                </template>
                <div v-if="!form.length" class="text-sm text-brand-secondary/50">
                    No button, please add one
                </div>
            </div>
            <div v-show="error" class="mt-1 form-error">{{ error }}</div>
            <div class="py-4 mt-4 border-t border-brand-secondary/10">
                <ButtonWhite @click="add" class="sm:text-xs">
                    <icon-heroicons-outline-plus-circle
                        class="inline w-4 h-4 mr-2 text-brand-primary"
                    />
                    Add Option
                </ButtonWhite>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';

const props = defineProps({
    modelValue: Array,
    error: String,
});

const form = reactive(props.modelValue || []);

const add = () => form.push({ value: `Option ${form.length + 1}`, feature: false });

const remove = (option, index) => form.splice(index, 1);

onMounted(() => {
    if (form.length === 0) {
        for (let i = 0; i <= 2; i++) add();
    }
});
</script>
