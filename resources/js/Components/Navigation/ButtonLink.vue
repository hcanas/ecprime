<script setup>
import {router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <PrimaryButton class="py-2"
                   @click="router.visit(href)">
        <slot />
    </PrimaryButton>
</template>
