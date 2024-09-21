<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import BaseLayout from "@/Layouts/BaseLayout.vue";

defineProps({
    trending_products: {
        type: Object,
        required: true,
    },
});

function redirectToProducts() {
    router.visit(route('shop'));
}
</script>

<template>
    <BaseLayout>
        <section class="w-full xl:max-w-7xl xl:mx-auto px-3 xl:px-0 py-6">
            <Head title="Home" />

            <div class="flex flex-col md:flex-row items-center md:justify-center md:gap-x-6 xl:space-x-12">
                <div>
                    <img class="h-80 md:h-[450px] xl:h-[650px]"
                         src="/images/hero2.png" />
                </div>
                <div class="flex flex-col items-center md:items-start">
                    <div class="flex flex-col items-center md:items-start">
                        <h1 class="uppercase text-4xl xl:text-6xl font-bold xl:leading-3">Making</h1>
                        <h1 class="uppercase text-4xl xl:text-7xl font-bold xl:leading-tight">Health Care</h1>
                        <h1 class="uppercase text-2xl xl:text-5xl font-medium xl:leading-3">Better Together!</h1>
                    </div>

                    <p class="dark:text-white font-medium mt-6 xl:mt-12">Great opportunities don't wait!</p>
                    <PrimaryButton class="w-fit mt-3"
                                   @click="redirectToProducts">Request Quotation Now
                    </PrimaryButton>
                </div>
            </div>

            <div class="flex flex-col items-center space-y-3 mt-12 xl:mt-0">
                <h2 class="dark:text-white font-bold">Trending Products</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 justify-center">
                    <Link v-for="product in trending_products"
                          :href="route('product.profile', { product: product.id })"
                          class="group border dark:bg-neutral-800 dark:border-neutral-800 rounded-md shadow transition ease-in-out p-2">
                        <div class="w-full p-1 rounded-t">
                            <img :src="product.image ? `/storage/images/${product.image}` : '/images/placeholder.svg'"
                                 class="mx-auto rounded w-[100px] h-[100px]" />
                        </div>
                        <p :title="product.name"
                           class="text-center text-xs group-hover:text-primary-500 dark:group-hover:text-primary-400 text-wrap line-clamp-3">{{ product.name }}</p>
                    </Link>
                </div>
                <SecondaryButton class="w-fit"
                                 @click="redirectToProducts">View More Products
                </SecondaryButton>
            </div>
        </section>
    </BaseLayout>
</template>
