<template>
    <div :class="[inline ? 'field-wrapper-inline' : 'field-wrapper']">
        <slot name="label" :label="label" :id="id">
            <label v-if="label" class="form-label" :for="`selector-${id}`">{{ label }}</label>
        </slot>
        <div class="relative form-wrapper">
            <ul
                role="list"
                :class="[
                    'overflow-hidden block w-full border rounded-md bg-brand-secondary/[0.03] dark:bg-brand-secondary/20',
                    'focus-within:outline-none focus-within:ring-1',
                    'disabled:select-none',
                    error
                        ? 'text-red-800 placeholder-red-400 border-red-500 focus-within:ring-red-500 focus-within:border-red-500 disabled:text-red-800/20'
                        : 'border-brand-secondary/20 placeholder-brand-secondary focus-within:ring-brand-secondary focus-within:border-brand-secondary text-brand-dark disabled:text-brand-dark/20 dark:text-white/80 dark:disabled:text-white/40',
                ]"
            >
                <li>
                    <label
                        class="flex px-2 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-brand-secondary/10"
                    >
                        <PhotoIcon v-if="type === 'image'" class="text-brand-secondary w-9 h-9" />
                        <PaperClipIcon v-else class="text-brand-secondary w-9 h-9" />
                        <div class="ml-3 select-none">
                            <p
                                :class="[
                                    'text-sm font-medium',
                                    error
                                        ? 'text-red-500'
                                        : 'text-brand-dark/80 dark:text-white/80',
                                ]"
                            >
                                Choose
                                {{ placeholder ? placeholder : label }}
                            </p>
                            <p
                                :class="[
                                    'text-xs',
                                    error ? 'text-rose-400' : 'text-brand-secondary',
                                ]"
                            >
                                Max file size {{ maxSize / 1000 / 1000 }} MB
                            </p>
                        </div>
                        <input
                            :id="`selector-${id}`"
                            @change="selectFile"
                            type="file"
                            :multiple="multiple"
                            class="hidden"
                            :accept="acceptProxy"
                        />
                    </label>
                </li>
                <draggable @change="draggableChange" v-model="files" item-key="name">
                    <template #item="{ element: file, index }">
                        <li
                            :class="[
                                'flex flex-wrap items-center',
                                index % 2 === 0 ? 'bg-brand-secondary/10' : '',
                            ]"
                        >
                            <div
                                :class="[
                                    file.type === 'image'
                                        ? [previewSizeClass, previewAspectClass]
                                        : 'w-12 h-12',
                                    previewSize === 'full' ? '' : 'p-3',
                                ]"
                            >
                                <div
                                    :class="[
                                        'flex items-center justify-center overflow-hidden ring-1 ring-brand-secondary/20',
                                        file.type === 'image'
                                            ? [previewSizeClass, previewAspectClass]
                                            : 'w-12 h-12',
                                        previewClass,
                                    ]"
                                >
                                    <ImageSafe
                                        v-if="file.type === 'image'"
                                        preview
                                        class="object-cover w-full h-full hover:opacity-90"
                                        :src="file.url"
                                        :alt="file.name"
                                        :title="file.name"
                                    />
                                    <DocumentIcon v-else class="text-brand-secondary w-9 h-9" />
                                </div>
                            </div>
                            <div
                                :class="[
                                    previewSize === 'full' ? 'w-full' : 'flex-1',
                                    'p-3 overflow-hidden',
                                ]"
                            >
                                <slot name="prepend-meta" :file="file" :index="index" />
                                <div
                                    class="flex items-center justify-between text-sm font-medium text-brand-dark dark:text-white/80"
                                >
                                    <div class="flex-1 overflow-hidden whitespace-nowrap">
                                        <div class="w-full truncate" :title="file.name">
                                            {{ file.name }}
                                        </div>
                                    </div>
                                    <div class="flex-none">
                                        <button
                                            @click="removeFile(file, index)"
                                            type="button"
                                            class="mx-3 text-red-400 hover:text-red-500"
                                        >
                                            <XMarkIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                                <div class="text-sm text-brand-secondary">
                                    {{ fileSize(file.size) }}
                                </div>
                                <slot name="append-meta" :file="file" :index="index" />
                                <div
                                    class="text-xs text-red-600"
                                    v-if="file.exceeded || errors?.[index]"
                                >
                                    {{ errors?.[index] || 'Size exceeds the limit' }}
                                </div>
                            </div>
                        </li>
                    </template>
                </draggable>
            </ul>
            <div v-if="error" class="mt-1 form-error">{{ error }}</div>
        </div>
    </div>
</template>

<script setup>
import { nanoid } from 'nanoid';
import ImageSafe from './Image.vue';
import { ref, computed, watch, onMounted } from 'vue';
import draggable from 'vuedraggable';
import { DocumentIcon, PaperClipIcon, PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const emit = defineEmits(['update:modelValue', 'fileRemoved']);
const props = defineProps({
    id: {
        type: String,
        default() {
            return `file-input-${nanoid()}`;
        },
    },
    type: {
        type: String,
        default: 'file',
    },
    placeholder: String,
    accept: String,
    modelValue: [String, Number, Array, Object],
    disabled: {
        type: Boolean,
        required: false,
    },
    label: String,
    error: String,
    inline: Boolean,
    previewUrl: String,
    previewClass: String,
    previewSize: {
        type: String,
        default: 'sm',
    },
    previewAspect: {
        type: String,
        default: 'square',
    },
    multiple: [Boolean],
    errors: [Array, Object],
    maxSize: {
        type: [Number, String],
        default: 2000000,
    },
});

const files = ref([]);

watch(
    () => props.modelValue,
    (value) => parseModelValue(value)
);

const parseModelValue = (value) => {
    if (value) {
        if (props.multiple) {
            files.value = [...value];
        } else {
            files.value = [value];
        }
    } else {
        files.value = [];
    }
};

onMounted(() => {
    parseModelValue(props.modelValue);
});

const acceptProxy = computed(() => {
    if (props.accept) return props.accept;
    if (props.type === 'image') return 'image/*';
});

const previewSizeClass = computed(
    () =>
        ({
            xs: 'w-12',
            sm: 'w-20',
            md: 'w-32',
            lg: 'w-48',
            xl: 'w-56',
            full: 'w-full',
        }[props.previewSize])
);

const previewAspectClass = computed(
    () =>
        ({
            square: 'aspect-square',
            auto: 'aspect-auto',
            video: 'aspect-video',
        }[props.previewAspect])
);

const removeFile = (file, index) => {
    if (confirm('Are you sure remove this file?')) {
        files.value.splice(index, 1);
        emit('fileRemoved', file);
        if (props.multiple) {
            emit('update:modelValue', files.value);
        } else {
            emit('update:modelValue', files.value[0]);
        }
    }
};

const fileSize = (size) => {
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(size) / Math.log(1024));
    return `${(size / Math.pow(1024, i)).toFixed(2)} ${sizes[i]}`;
};

const selectFile = (event) => {
    if (event.target?.files?.length > 0) {
        if (!props.multiple) files.value = [];

        Object.values(event.target.files).forEach((item) => {
            var size = item.size;
            var type = item.type.indexOf('image/') !== -1 ? 'image' : 'file';

            files.value.push({
                name: item.name,
                size,
                type,
                exceeded: item.size > props.maxSize,
                url: URL.createObjectURL(item),
                uploaded: false,
                object: item,
            });
        });

        if (props.multiple) {
            emit('update:modelValue', files.value);
        } else {
            emit('update:modelValue', files.value[0]);
        }

        event.target.value = null;
    }
};

const draggableChange = ({ moved }) => {
    if (props.multiple) {
        emit('update:modelValue', files.value);
    } else {
        emit('update:modelValue', files.value[0]);
    }
};
</script>
