<script setup>
const colors = {
    success: 'text-green-600 bg-green-50 border-green-100 bg-green-50 dark:text-green-500 dark:border-neutral-800',
    error: 'text-red-600 bg-red-50 border-red-100 dark:text-red-500 dark:border-neutral-800',
    default: 'text-blue-600 bg-blue-50 border-blue-100 dark:text-blue-500 dark:border-neutral-800',
};
</script>

<template>
    <p v-if="$page.props.flash.message"
       :class="colors[$page.props.flash.message.type]"
       class="w-full px-3 py-2 my-3 border rounded-md dark:bg-neutral-800">
        {{ $page.props.flash.message.content }}
    </p>
</template>
