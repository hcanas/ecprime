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

        <div class="xl:max-w-5xl xl:mx-auto flex flex-col md:flex-row md:gap-x-6 gap-y-6">
            <img :src="product.image ? `/storage/images/${product.image}` : '/images/placeholder.svg'"
                 class="md:size-96 rounded-md" />

            <div class="flex-grow flex flex-col gap-y-6">
                <div class="flex flex-col gap-y-3">
                    <h1 class="font-medium">{{ product.name }}</h1>
                    <p class="flex flex-col bg-neutral-100 dark:bg-neutral-900 p-3 rounded-md">
                        <span class="text-sm text-neutral-500">Description</span>
                        <span>{{ product.description }}</span>
                    </p>
                </div>

                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-y-3">
                    <p class="text-sm italic">Sold per {{ product.measurement_unit }}</p>
                    <PrimaryButton v-if="!itemExists(product.id)"
                                   class="xl:w-44"
                                   @click="addItem(product.id)">Add To Cart
                    </PrimaryButton>
                    <DangerButton v-else
                                  class="xl:w-44"
                                  @click="removeItem(product.id)">Remove From Cart
                    </DangerButton>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>
