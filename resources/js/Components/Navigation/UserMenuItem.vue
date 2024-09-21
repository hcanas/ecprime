<script setup>
import {MenuItem} from "@headlessui/vue";
import {Link} from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    as: {
        type: String,
    },
    method: {
        type: String
    },
});
</script>

<template>
    <MenuItem class="w-full px-3 py-2 transition duration-150 ease-in-out">
        <Link :as="as"
              :href="href"
              :method="method"
              class="text-sm font-medium text-neutral-400 hover:text-primary-500 dark:hover:text-primary-400">
            <slot />
        </Link>
    </MenuItem>
</template>
