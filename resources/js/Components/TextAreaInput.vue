<script setup>
import {ref} from "vue";

const model = defineModel({
    type: String,
});

const input = ref(null);

defineExpose({
    input,
})
</script>

<template>
    <textarea ref="input"
              v-model="model"
              class="px-3 py-2 bg-neutral-100 border-gray-300 rounded-md shadow-sm outline-none focus:ring-0" />
</template>
