<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import Form from "@/Pages/Products/Partials/Form.vue";
import PageHead from "@/Components/PageHead.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
</script>

<template>
    <BaseLayout>
        <PageHead class="xl:max-w-3xl mx-auto"
                  title="New Product" />

        <div class="xl:max-w-3xl mx-auto">
            <FlashMessage />
            <Form />
        </div>
    </BaseLayout>
</template>
