<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link} from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center px-3 py-12">
        <div>
            <Link href="/">
                <ApplicationLogo class="size-12" />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-neutral-100 dark:bg-neutral-800 shadow-md overflow-hidden rounded-md"
        >
            <slot />
        </div>
    </div>
</template>
