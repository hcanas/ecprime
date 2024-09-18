<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    active: Boolean,
});
</script>

<template>
    <Link :class="active ? 'text-primary-500 dark:text-primary-400' : 'text-neutral-300 dark:text-neutral-600'"
          class="font-bold hover:text-primary-500 dark:hover:text-primary-400 px-3 py-2 rounded transition-colors ease-in-out">
        <slot />
    </Link>
</template>
