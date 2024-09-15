<script setup>
import {Head} from "@inertiajs/vue3";

defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="flex items-end justify-between mb-6">
        <Head :title="title" />
        <h1 class="flex-shrink-0 text-neutral-700 font-bold">{{ title }}</h1>
        <div>
            <slot />
        </div>
    </div>
</template>
