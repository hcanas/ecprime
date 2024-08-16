<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Form from "@/Pages/Products/Partials/Form.vue";
</script>

<template>
    <Head title="New Product" />

    <AuthenticatedLayout>
        <Form />
    </AuthenticatedLayout>
</template>
