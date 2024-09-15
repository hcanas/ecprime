<script setup>
const colors = {
    success: 'text-green-600 border-green-100 bg-green-50',
    error: 'text-red-600 border-red-100 bg-red-50',
    default: 'text-blue-600 border-blue-100 bg-blue-50',
};
</script>

<template>
    <p v-if="$page.props.flash.message"
       :class="colors[$page.props.flash.message.type]"
       class="w-full px-3 py-2 my-3 border rounded-md">
        {{ $page.props.flash.message.content }}
    </p>
</template>
