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
    <MenuItem class="block w-full px-3 py-2 text-start transition duration-150 ease-in-out">
        <Link :as="as"
              :href="href"
              :method="method"
              class="text-sm text-neutral-500 hover:text-primary-500">
            <slot />
        </Link>
    </MenuItem>
</template>
