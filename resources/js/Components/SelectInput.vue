<script setup>
const model = defineModel({
    type: String,
    required: true,
});

defineProps({
    options: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <select v-model="model"
            class="w-full py-2 bg-neutral-100 border-neutral-300 dark:text-neutral-100 dark:border-neutral-700 dark:bg-neutral-700 rounded-md focus:ring-0">
        <option v-for="option in options"
                :value="option.value">{{ option.label }}
        </option>
    </select>
</template>
