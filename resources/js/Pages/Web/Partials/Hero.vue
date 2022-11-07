<template>
    <div class="flex max-w-6xl py-20 mx-auto">
        <div class="flex-1 py-10 space-y-10 text-brand-light">
            <h1 class="md:text-5xl font-semibold max-w-md md:leading-[3.75rem">
                Balai Pelestarian Cagar Budaya Provinsi Kalimantan Timur
            </h1>
            <div class="max-w-sm">
                Wilayah kerja Provinsi Kalimantan, Kalimantan Utara, Kalimantan Selatan, Kalimantan
                Tengah dan Kalimantan Barat.
            </div>
            <div>
                <form class="relative flex max-w-xs">
                    <input
                        :class="[
                            'px-4 py-3 border-y border-l placeholder-[#D2D6DC] border-brand-secondary text-brand-dark flex-1',
                            'focus:ring-0 focus:outline-none',
                        ]"
                        placeholder="Masukan Cagar Budaya"
                    />
                    <button
                        type="submit"
                        class="px-6 py-3 font-medium text-white transition-all bg-brand-dark hover:bg-brand-secondary focus:bg-brand-secondary/90"
                    >
                        Cari
                    </button>
                </form>
            </div>
        </div>
        <div>
            <div class="relative max-w-md aspect-square">
                <Transition v-for="(item, index) in slider" v-if="slider.length">
                    <Link :href="item.url">
                        <div class="min-w-[28rem]">
                            <img
                                v-if="show[index]"
                                :id="item.id"
                                :src="item?.image?.url"
                                alt="Promo"
                                class="absolute z-10 object-cover w-full h-full"
                            />
                        </div>
                    </Link>
                </Transition>
                <img
                    v-else
                    :src="`/img/slider-1.png`"
                    alt="Promo"
                    class="relative z-10 object-cover w-full h-full"
                />
                <div
                    class="absolute z-0 w-full h-full border border-brand-gray -bottom-6 -right-6"
                ></div>
            </div>
        </div>
    </div>
</template>

<script setup>
const slider = computed(() => usePage().props.value?.slider);
const show = ref([]);
const interval = ref(null);

const updateTicker = () => {
    const removed = show.value.pop();
    show.value.unshift(removed);
};

const initTicker = () => {
    if (slider.value) {
        show.value = [];
        slider.value?.forEach((item, index) => {
            show.value.push(index === 0);
        });
        interval.value = setInterval(updateTicker, 5000);
    }
};

watch(
    () => slider.value,
    () => initTicker()
);
onMounted(() => initTicker());
onBeforeUnmount(() => clearInterval(interval.value));
</script>
<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 1s;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
