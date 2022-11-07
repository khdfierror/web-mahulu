<template>
    <div class="px-4 text-white py-28 bg-brand-primary sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-center max-w-6xl mx-auto">
            <h2 class="text-4xl font-semibold">Jendela</h2>
            <div class="flex items-center justify-center gap-[52px] mt-20">
                <button
                    @click="prev"
                    class="flex border border-brand-gray hover:bg-white hover:text-black"
                >
                    <Icon class="flex items-center justify-center w-12 h-12" name="arrow-left" />
                </button>
                <div class="grid grid-cols-2 gap-8 min-h-[30rem]">
                    <div
                        v-for="(item, index) in 2"
                        :key="index"
                        class="overflow-hidden transition-all ease-in-out duration-1000 delay-200"
                    >
                        <Link
                            :href="`${
                                show[index]?.slug
                                    ? route('web.layanan.show', { slug: show[index]?.slug })
                                    : '#'
                            }`"
                        >
                            <img
                                :src="show[index]?.image"
                                :alt="show[index]?.slug"
                                class="relative z-10 mb-7 aspect-video object-cover"
                            />
                        </Link>
                        <div class="mb-3 font-semibold">{{ show[index]?.title }}</div>
                        <div class="text-brand-light" v-html="show[index]?.content"></div>
                    </div>
                </div>
                <button
                    @click="next"
                    class="flex border border-brand-gray hover:bg-white hover:text-black"
                >
                    <Icon class="flex items-center justify-center w-12 h-12" name="arrow-right" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import Icon from '@/Shared/Icon.vue';
const props = defineProps({
    jendela: Object,
});

const show = ref([props.jendela?.[0], props.jendela?.[1]]);

const prev = () => {
    const removed = show.value.pop();
    let index =
        props.jendela.findIndex((item) => {
            return item.id === show.value[0].id;
        }) - 1;
    console.log(index, show.value);

    if (index < 0) {
        index = props.jendela.length - 1;
    }

    show.value.unshift(props.jendela[index]);
};

const next = () => {
    const removed = show.value.shift();
    let index =
        props.jendela.findIndex((item) => {
            return item.id === show.value[0].id;
        }) + 1;

    if (index >= props.jendela.length) {
        index = 0;
    }

    show.value.push(props.jendela[index]);
};
</script>
<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
