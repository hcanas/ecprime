<script setup>
defineProps({
    title: {
        type: String,
    },
});
</script>

<template>
    <div class="flex flex-col border dark:bg-neutral-900 dark:border-neutral-800 rounded-md shadow">
        <div v-if="title"
             class="p-3">
            <h4 class="font-bold tracking-wide">
                {{ title }}
            </h4>
        </div>
        <div class="px-3 py-3">
            <slot />
        </div>
    </div>
</template>
