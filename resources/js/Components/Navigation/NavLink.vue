<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    active: Boolean,
});
</script>

<template>
    <Link :class="active ? 'text-primary-500' : 'text-neutral-500'"
          class="font-medium hover:text-primary-500 px-3 py-2 rounded transition-colors ease-in-out">
        <slot />
    </Link>
</template>
