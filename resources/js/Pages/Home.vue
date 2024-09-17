<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {onMounted} from "vue";
import {useFormatter} from "@/Composables/formatter.js";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import BaseLayout from "@/Layouts/BaseLayout.vue";

defineProps({
    trending_products: {
        type: Object,
        required: true,
    },
});

const {formatNumber} = useFormatter();

function redirectToProducts() {
    router.visit(route('shop'));
}

onMounted(() => {
    axios.get(route('best_sellers'))
        .then(response => bestSellers.value = response.data);
});
</script>

<template>
    <BaseLayout>
        <section class="w-full md:max-w-7xl md:mx-auto px-3 md:px-0 py-6">
            <Head title="Home" />

            <div class="flex flex-col md:flex-row items-center md:justify-center md:space-x-12">
                <div>
                    <img class="h-80 md:h-[650px]"
                         src="/images/hero2.png" />
                </div>
                <div class="flex flex-col items-center md:items-start">
                    <div class="flex flex-col items-center md:items-start">
                        <h1 class="uppercase text-4xl md:text-6xl font-bold leading-3">Making</h1>
                        <h1 class="uppercase text-5xl md:text-7xl font-bold leading-tight">Health Care</h1>
                        <h1 class="uppercase text-3xl md:text-5xl font-medium leading-3">Better Together!</h1>
                    </div>

                    <p class="text-lg font-medium mt-12">Great opportunities don't wait!</p>
                    <PrimaryButton class="w-fit mt-3"
                                   @click="redirectToProducts">Request Quotation Now
                    </PrimaryButton>
                </div>
            </div>

            <div class="flex flex-col items-center space-y-3 mt-12 md:mt-0">
                <h3 class="text-2xl font-bold">Trending Products</h3>
                <div class="grid grid-cols-2 md:grid-cols-8 gap-6 justify-center">
                    <Link v-for="product in trending_products"
                          :href="route('product.profile', { product: product.id })"
                          class="hover:text-primary-500 border hover:border-primary-500 dark:text-white dark:hover:text-primary-500 dark:bg-neutral-900 dark:border-neutral-800 dark:hover:border-primary-500 rounded-md shadow transition ease-in-out">
                        <div class="w-full p-1 rounded-t">
                            <img :src="product.image ? `/storage/images/${product.image}` : '/images/placeholder.png'"
                                 class="mx-auto rounded w-[100px] h-[100px]" />
                        </div>
                        <div class="flex flex-col items-center space-y-1 px-4 py-2">
                            <p :title="product.name"
                               class="w-full text-center text-xs">{{ product.name }}</p>
                        </div>
                    </Link>
                </div>
                <SecondaryButton class="w-fit"
                                 @click="redirectToProducts">View More Products
                </SecondaryButton>
            </div>
        </section>
    </BaseLayout>
</template>
