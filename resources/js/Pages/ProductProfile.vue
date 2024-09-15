<script setup>
import {Head} from "@inertiajs/vue3";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useCart} from "@/Composables/cart.js";
import DangerButton from "@/Components/DangerButton.vue";

defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const {addItem, removeItem, itemExists} = useCart();
</script>

<template>
    <BaseLayout>
        <Head :title="product.name" />

        <div class="max-w-7xl mx-auto">
            <div class="flex items-start space-x-12">
                <img :src="product.image ? `/storage/images/${product.image}` : '/images/placeholder.png'"
                     class="w-[600px] h-[600px]" />

                <div class="flex-grow flex flex-col space-y-6">
                    <div class="flex flex-col space-y-3">
                        <h1 class="font-medium">{{ product.name }}</h1>
                        <p class="flex flex-col bg-neutral-100 p-3 rounded-md">
                            <span class="text-sm text-neutral-500">Description</span>
                            <span>{{ product.description }}</span>
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="text-sm italic">Sold per {{ product.measurement_unit }}</p>
                        <PrimaryButton v-if="!itemExists(product.id)"
                                       class="w-44"
                                       @click="addItem(product.id)">Add To Cart
                        </PrimaryButton>
                        <DangerButton v-else
                                      class="w-44"
                                      @click="removeItem(product.id)">Remove From Cart
                        </DangerButton>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>
