<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    active: Boolean,
});
</script>

<template>
    <Link class="font-medium hover:text-primary-400 px-3 py-2 rounded transition-colors ease-in-out" :class="active ? 'text-primary-500' : 'text-neutral-400'">
        <slot />
    </Link>
</template>
