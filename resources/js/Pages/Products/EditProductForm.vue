<script setup>
import {Head, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Form from "@/Pages/Products/Partials/Form.vue";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

function deleteProduct() {
    router.delete(route('products.destroy', { product: props.product.id }), {
        onBefore: () => confirm('This product will be deleted permanently.'),
    });
}
</script>

<template>
    <Head title="Edit Product" />

    <AuthenticatedLayout>
        <Form :product="product" />

        <div class="mt-6">
            <p>Deleting this product will permanently remove all records. This action is not reversible.</p>
            <button @click="deleteProduct">Delete Product</button>
        </div>
    </AuthenticatedLayout>
</template>
