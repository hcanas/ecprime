<script setup>
import {router} from "@inertiajs/vue3";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import Form from "@/Pages/Products/Partials/Form.vue";
import PageHead from "@/Components/PageHead.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {useFormatter} from "@/Composables/formatter.js";
import FlashMessage from "@/Components/FlashMessage.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const {formatDateTime} = useFormatter();

function deleteProduct() {
    router.delete(route('products.destroy', {product: props.product.id}), {
        onBefore: () => confirm('This product will be deleted permanently.'),
    });
}
</script>

<template>
    <BaseLayout>
        <PageHead class="max-w-3xl mx-auto"
                  title="Edit Product" />

        <div class="max-w-3xl mx-auto flex flex-col">
            <FlashMessage />

            <Form :product="product" />

            <Card class="border-red-300 mt-12"
                  title="Danger Zone">
                <div class="flex items-center justify-between space-x-6">
                    <div class="flex flex-col text-sm">
                        <p class="font-medium">Product Deletion</p>
                        <p class="text-neutral-500">Deleting this product will permanently remove it from the database. This action is irreversible.</p>
                        <p class="text-neutral-500 italic">*** Products with linked records can not be deleted.</p>
                    </div>
                    <DangerButton class="flex-shrink-0"
                                  @click="deleteProduct">Delete Product
                    </DangerButton>
                </div>
            </Card>
        </div>
    </BaseLayout>
</template>
