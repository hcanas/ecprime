<script setup>
import {computed} from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input
        v-model="proxyChecked"
        :value="value"
        class="rounded border-gray-300 text-primary-500 shadow-sm focus:ring-primary-500 dark:text-primary-500 dark:focus:ring-primary-400"
        type="checkbox"
    />
</template>
