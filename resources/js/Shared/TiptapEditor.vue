<template>
    <div :class="[inline ? 'field-wrapper-inline' : 'field-wrapper']">
        <slot name="label" :label="label" :id="id">
            <label v-if="label" :for="id" class="form-label">{{ label }}</label>
        </slot>
        <div class="form-wrapper">
            <div
                :class="[
                    'flex flex-col flex-1 rounded-md bg-brand-secondary/[0.03] dark:bg-brand-secondary/20 border',
                    error
                        ? 'text-red-800 placeholder-red-400 border-red-500 focus-within:ring-red-500 focus-within:border-red-500 disabled:text-red-800/20'
                        : 'border-brand-secondary/20 placeholder-brand-secondary focus-within:ring-brand-secondary focus-within:border-brand-secondary text-brand-dark disabled:text-brand-dark/20 dark:text-white/80 dark:disabled:text-white/40',
                    'relative',
                    'transition',
                    'focus-within:ring-1',
                ]"
            >
                <div
                    class="sticky top-0 z-50 flex overflow-hidden bg-white border-b rounded-t-md dark:bg-brand-dark/80 border-brand-secondary/20"
                >
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().toggleBold().run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Bold'"
                        v-if="featureInclude('bold')"
                    >
                        <icon-fa-bold class="w-4 h-4" />
                    </button>
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().toggleItalic().run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Italic'"
                        v-if="featureInclude('italic')"
                    >
                        <icon-fa-italic class="w-4 h-4" />
                    </button>
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().toggleUnderline().run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Underline'"
                        v-if="featureInclude('underline')"
                    >
                        <icon-fa-underline class="w-4 h-4" />
                    </button>
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Header 2'"
                        v-if="featureInclude('header2')"
                    >
                        <icon-fa-header class="w-4 h-4" /><sup>2</sup>
                    </button>
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Header 3'"
                        v-if="featureInclude('header3')"
                    >
                        <icon-fa-header class="w-4 h-4" /><sup>3</sup>
                    </button>
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().setParagraph().run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Paragraph'"
                        v-if="featureInclude('paragraph')"
                    >
                        <icon-fa-paragraph class="w-4 h-4" />
                    </button>
                    <Popover class="relative">
                        <PopoverButton
                            tabindex="-1"
                            v-tooltip="'Text Alignment'"
                            v-if="featureInclude('text-alignment')"
                            class="editor-toolbar-button"
                        >
                            <icon-fa-text-format class="w-4 h-4" />
                        </PopoverButton>

                        <PopoverPanel class="absolute z-10 mt-1">
                            <div
                                class="flex overflow-hidden bg-white rounded shadow dark:bg-brand-dark/80"
                            >
                                <button
                                    tabindex="-1"
                                    @click="editor.chain().focus().setTextAlign('left').run()"
                                    type="button"
                                    class="editor-toolbar-button"
                                >
                                    <icon-fa-text-align-left class="w-5 h-5" />
                                </button>
                                <button
                                    tabindex="-1"
                                    @click="editor.chain().focus().setTextAlign('center').run()"
                                    type="button"
                                    class="editor-toolbar-button"
                                >
                                    <icon-fa-text-align-center class="w-5 h-5" />
                                </button>
                                <button
                                    tabindex="-1"
                                    @click="editor.chain().focus().setTextAlign('right').run()"
                                    type="button"
                                    class="editor-toolbar-button"
                                >
                                    <icon-fa-text-align-right class="w-5 h-5" />
                                </button>
                                <button
                                    tabindex="-1"
                                    @click="editor.chain().focus().setTextAlign('justify').run()"
                                    type="button"
                                    class="editor-toolbar-button"
                                >
                                    <icon-fa-text-align-justify class="w-5 h-5" />
                                </button>
                            </div>
                        </PopoverPanel>
                    </Popover>
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().clearNodes().unsetAllMarks().run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Remove Formatting'"
                        v-if="featureInclude('removeformat')"
                    >
                        <icon-fa-eraser class="w-4 h-4" />
                    </button>
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().toggleBulletList().run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Bullet List'"
                        v-if="featureInclude('bullet-list')"
                    >
                        <icon-fa-list-bullet class="w-4 h-4" />
                    </button>
                    <button
                        tabindex="-1"
                        @click="editor.chain().focus().toggleOrderedList().run()"
                        type="button"
                        class="editor-toolbar-button"
                        v-tooltip="'Ordered List'"
                        v-if="featureInclude('ordered-list')"
                    >
                        <icon-fa-list-order class="w-4 h-4" />
                    </button>
                    <div
                        class="w-[1px] bg-gray-200 dark:bg-brand-secondary/10 my-2 mx-2"
                        v-if="featureInclude('image')"
                    ></div>
                    <label
                        class="editor-toolbar-button"
                        v-tooltip="'Insert Image'"
                        v-if="featureInclude('image')"
                    >
                        <input type="file" @input="selectImage" class="hidden" accept="image/*" />
                        <icon-fa-image class="w-4 h-4" />
                    </label>
                </div>
                <bubble-menu
                    plugin-key="bubble-menu-image"
                    :typpy-options="{ maxWidth: 'none' }"
                    :should-show="shouldBubbleMenuShow"
                    :editor="editor"
                    v-if="editor"
                    class="-m-0.5"
                >
                    <button
                        tabindex="-1"
                        v-for="item in [
                            'rounded-none',
                            'rounded',
                            'rounded-md',
                            'rounded-lg',
                            'rounded-xl',
                            'rounded-2xl',
                            'rounded-3xl',
                        ]"
                        type="button"
                        :class="[
                            'px-2 py-0.5 m-0.5  text-xs rounded shadow ring-1 ring-brand-secondary',
                            editor.isActive('image', { class: `/${item}*/` })
                                ? 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80'
                                : 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80',
                        ]"
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleClass({
                                    class: item,
                                    remove: new RegExp(/rounded-?/, 'g'),
                                })
                                .run()
                        "
                    >
                        {{ item }}
                    </button>
                    <button
                        tabindex="-1"
                        v-for="item in ['border', 'border-2', 'border-3']"
                        type="button"
                        :class="[
                            'px-2 py-0.5 m-0.5  text-xs rounded shadow ring-1 ring-brand-secondary',
                            editor.isActive('image', { class: `/${item}*/` })
                                ? 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80'
                                : 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80',
                        ]"
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleClass({
                                    class: 'border '.concat(item),
                                    remove: new RegExp(/border-?/, 'g'),
                                })
                                .run()
                        "
                    >
                        {{ item }}
                    </button>
                    <button
                        tabindex="-1"
                        v-for="item in ['w-full', 'w-1/2', 'w-1/3']"
                        type="button"
                        :class="[
                            'px-2 py-0.5 m-0.5  text-xs rounded shadow ring-1 ring-brand-secondary',
                            editor.isActive('image', { class: `/${item}*/` })
                                ? 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80'
                                : 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80',
                        ]"
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleClass({
                                    class: item,
                                    remove: new RegExp(/w-?/, 'g'),
                                })
                                .run()
                        "
                    >
                        {{ item }}
                    </button>
                    <button
                        tabindex="-1"
                        v-for="item in [
                            'block',
                            'inline',
                            'float-none',
                            'float-left',
                            'float-right',
                        ]"
                        type="button"
                        :class="[
                            'px-2 py-0.5 m-0.5  text-xs rounded shadow ring-1 ring-brand-secondary',
                            editor.isActive('image', { class: `/${item}*/` })
                                ? 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80'
                                : 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80',
                        ]"
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleClass({
                                    class: item,
                                    remove: new RegExp(/float-?|inline|block/, 'g'),
                                })
                                .run()
                        "
                    >
                        {{ item }}
                    </button>
                    <button
                        tabindex="-1"
                        v-for="item in ['p-0', 'p-0.5', 'p-1', 'p-2', 'p-3']"
                        type="button"
                        :class="[
                            'px-2 py-0.5 m-0.5  text-xs rounded shadow ring-1 ring-brand-secondary',
                            editor.isActive('image', { class: `/${item}*/` })
                                ? 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80'
                                : 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80',
                        ]"
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleClass({
                                    class: item,
                                    remove: new RegExp(/p-?/, 'g'),
                                })
                                .run()
                        "
                    >
                        {{ item }}
                    </button>
                    <button
                        tabindex="-1"
                        v-for="item in ['m-0', 'm-0.5', 'm-1', 'm-2', 'm-3']"
                        type="button"
                        :class="[
                            'px-2 py-0.5 m-0.5  text-xs rounded shadow ring-1 ring-brand-secondary',
                            editor.isActive('image', { class: `/${item}*/` })
                                ? 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80'
                                : 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80',
                        ]"
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleClass({
                                    class: item,
                                    remove: new RegExp(/m-?/, 'g'),
                                })
                                .run()
                        "
                    >
                        {{ item }}
                    </button>
                    <button
                        tabindex="-1"
                        v-for="item in ['filter-none', 'grayscale', 'blur-none', 'blur']"
                        type="button"
                        :class="[
                            'px-2 py-0.5 m-0.5  text-xs rounded shadow ring-1 ring-brand-secondary',
                            editor.isActive('image', { class: `/${item}*/` })
                                ? 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80'
                                : 'bg-brand-secondary/20 backdrop-blur-sm dark:bg-brand-dark/80',
                        ]"
                        @click="
                            editor
                                .chain()
                                .focus()
                                .toggleClass({
                                    class: 'filter '.concat(item),
                                    remove: new RegExp(/blur-?|grayscale|filter-?/, 'g'),
                                })
                                .run()
                        "
                    >
                        {{ item }}
                    </button>
                </bubble-menu>
                <editor-content
                    :editor="editor"
                    class="flex flex-col flex-1 overflow-y-auto bg-brand-secondary/[0.03] dark:bg-white/80 rounded-b-md"
                />
            </div>
            <div v-if="error" class="mt-1 form-error">{{ error }}</div>
            <slot name="help">
                <div class="mt-1 text-xs text-brand-secondary" v-if="help">
                    {{ help }}
                </div>
            </slot>
        </div>
    </div>
</template>

<style>
.tooltip {
    @apply invisible absolute;
}

.v-popper__inner {
    @apply text-sm;
}

.editor-toolbar-button {
    @apply inline-flex items-center cursor-pointer px-2.5 py-2 font-medium
        hover:bg-brand-secondary/20 backdrop-blur-sm active:bg-brand-primary active:text-white
        dark:hover:bg-brand-secondary/20;
}

.ProseMirror {
    @apply h-full px-2 py-3 active:outline-none focus:outline-none;
}

.ProseMirror {
    @apply max-w-none;
}

.ProseMirror > * + * {
    margin-top: 0.75em;
}

.ProseMirror img {
    max-width: 100%;
    height: auto;
}

.ProseMirror img.ProseMirror-selectednode {
    outline: 3px solid #68cef8;
}

.prose {
    line-height: 1.5;
}

.prose > ol > li > *:first-child,
.prose > ol > li > *:last-child,
.prose > ul > li > *:first-child,
.prose > ul > li > *:last-child {
    @apply my-0;
}

.prose li {
    @apply my-0.5;
}

.prose p {
    margin: 0;
}
</style>

<script setup>
import { nanoid } from 'nanoid';
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue';

import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { Editor, EditorContent, BubbleMenu } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import TextAlign from '@tiptap/extension-text-align';
// import Image from '@tiptap/extension-image'
// import TiptapEditoStyle from "./TiptapEditorStyle";
import Image from './TiptapEditorImage';

import IconFaBold from '~icons/fa/bold';
import IconFaUnderline from '~icons/fa/underline';
import IconFaItalic from '~icons/fa/italic';
import IconFaHeader from '~icons/fa/header';
import IconFaEraser from '~icons/fa/eraser';
import IconFaImage from '~icons/fa/image';
import IconFaParagraph from '~icons/fa/paragraph';
import IconFaListBullet from '~icons/fa/list-ul';
import IconFaListOrder from '~icons/fa/list-ol';

import IconFaTextAlignJustify from '~icons/fa/align-justify';
import IconFaTextAlignCenter from '~icons/fa/align-center';
import IconFaTextAlignLeft from '~icons/fa/align-left';
import IconFaTextAlignRight from '~icons/fa/align-right';
import IconFaTextFormat from '~icons/fa/indent';

const id = `editor-input-${nanoid()}`;

const emit = defineEmits(['update:modelValue', 'inserted-image']);

const props = defineProps({
    inline: Boolean,
    label: String,
    error: String,
    help: String,
    modelValue: String,
    editorClass: {
        type: String,
        default: 'prose focus:outline-none min-h-[200px] flex-1',
    },
    type: {
        type: String,
        default: 'full',
    },
});

const editor = ref(null);

watch(
    () => props.modelValue,
    (value) => {
        const isSame = editor.value?.getHTML() === value;

        if (isSame) {
            return;
        }

        editor.value?.commands?.setContent(value, false);
    }
);

const images = ref([]);

const shouldBubbleMenuShow = ({ state }) => state.selection.node?.type?.name === 'image';
const handlerMenuImage = (editor, group, event) => {
    const { value } = event.target;
    editor.toggleClass('rounded-lg');
};

const features = computed(() => {
    if (props.type === 'simple') {
        return ['bold', 'italic', 'removeformat'];
    } else {
        return [
            'bold',
            'italic',
            'header2',
            'header3',
            'text-alignment',
            'bullet-list',
            'bullet-list',
            'ordered-list',
            'removeformat',
            'image',
        ];
    }
});

const featureInclude = (feature) => features.value.indexOf(feature) !== -1;

const selectImage = (e) => {
    const files = e.target.files;

    Object.keys(files).forEach((key) => {
        const file = files[key];
        const url = URL.createObjectURL(file);
        const id = url.split('/').reverse()?.[0];
        images.value.push({
            id,
            name: file.name,
            url,
            object: file,
        });

        console.log(editor.value.chain().focus());

        editor.value.chain().focus().setImage({ src: url, id }).run();
    });

    e.target.value = null;
};

onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue,
        extensions: [
            StarterKit,
            Underline,
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            Image,
            // BubbleMenu.configure({
            //   pluginKey: 'bubleMenuImage',
            //   element: document.querySelector('img[src]')
            // })
        ],
        editorProps: {
            attributes: {
                class: props.editorClass,
            },
        },
        onUpdate: () => {
            let content = editor.value?.getHTML();
            if (content === '<p></p>') content = '';

            images.value.forEach((item, index) => {
                if (content.indexOf(item.url) === -1) {
                    images.value.splice(index, 1);
                }
            });
            emit('update:modelValue', content);
            emit('inserted-image', images.value);
        },
    });
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});
</script>
