<script setup>
const news = computed(() => usePage().props.value?.tickers);
const show = ref([]);
const interval = ref(null);

const updateTicker = () => {
    const removed = show.value.pop();
    show.value.unshift(removed);
};

const initTicker = () => {
    if (news.value) {
        show.value = [];
        news.value?.forEach((item, index) => {
            show.value.push(index === 0);
        });
        interval.value = setInterval(updateTicker, 5000);
    }
};

watch(
    () => news.value,
    () => initTicker()
);
onMounted(() => initTicker());
onBeforeUnmount(() => clearInterval(interval.value));
</script>

<template>
    <div class="flex max-w-7xl mx-auto" v-if="news.length">
        <div class="py-5 font-bold text-white">&nbsp;</div>
        <div class="relative flex-1 bg-white text-center">
            <!-- <Transition v-for="(item, index) in news">
                <div class="absolute inset-0 px-10 py-5" v-if="show[index]" :id="item.id">
                    <Link :href="item.link" v-html="item.content" />
                </div>
            </Transition> -->
            <marquee 
                direction="left" 
                behavior="scroll" 
                class="absolute inset-0 py-5" 
                scrollamount="10"
                onmouseover="this.stop()" 
                onmouseout="this.start()"
            >
                <div class="flex flex-row gap-96">
                    <div class="" v-for="(item, index) in news" :id="item.id">
                        <Link :href="item.link" v-html="item.content" />
                    </div>
                </div>
            </marquee>
            <!-- <Transition v-for="(item, index) in news">
                <div class="absolute inset-0 px-10 py-5" v-if="show[index]" :id="item.id">
                    <Link :href="item.link" v-html="item.content" />
                </div>
            </Transition> -->
        </div>
    </div>
</template>

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
