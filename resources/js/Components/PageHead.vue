<script setup>
import {Head} from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    class: {
        type: String,
    },
});
</script>

<template>
    <Head :title="props.title" />

    <div :class="props.class" class="flex flex-col md:flex-row md:justify-between md:gap-x-1 gap-y-3 md:gap-y-0 mb-6">
        <div class="flex-grow flex items-end justify-between">
            <h1 class="flex-shrink-0 font-bold">{{ props.title }}</h1>
            <div>
                <slot name="actions" />
            </div>
        </div>
        <div class="flex-shrink-0 flex flex-col md:flex-row md:gap-x-1 gap-y-1 md:gap-y-0">
            <slot name="filters" />
        </div>
    </div>
</template>
