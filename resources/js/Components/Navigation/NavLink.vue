<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    active: Boolean,
});
</script>

<template>
    <Link :class="active ? 'text-primary-500' : 'text-neutral-400 dark:text-neutral-600'"
          class="font-medium xl:hover:text-primary-500 dark:xl:hover:text-primary-400 px-3 py-2 transition-colors ease-in-out">
        <slot />
    </Link>
</template>
