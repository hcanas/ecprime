<script setup>
import {onMounted, ref} from 'vue';

const model = defineModel();

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <input
        ref="input"
        v-model="model"
        class="px-3 py-2 bg-neutral-100 border-gray-300 rounded-md shadow-sm outline-none focus:ring-0"
    />
</template>
