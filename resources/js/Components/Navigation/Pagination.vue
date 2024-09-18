<script setup>
import {Link} from "@inertiajs/vue3";
import {
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon
} from "@heroicons/vue/16/solid/index.js";

defineProps({
    currentPage: {
        type: Number,
        required: true,
    },
    perPage: {
        type: Number,
        required: true,
    },
    from: {
        type: Number,
        required: true,
    },
    to: {
        type: Number,
        required: true,
    },
    totalRecords: {
        type: Number,
        required: true,
    },
    prevPageUrl: {
        type: String,
    },
    nextPageUrl: {
        type: String,
    },
    firstPageUrl: {
        type: String,
    },
    lastPageUrl: {
        type: String,
    },
});
</script>

<template>
    <div v-if="totalRecords > perPage"
         class="w-full md:w-auto flex flex-col md:flex-row items-center justify-between md:space-x-3 md:order-last mt-6">
        <p v-if="totalRecords > 1"
           class="order-2 text-sm md:order-first">Showing {{ from }} to {{ to }} of {{ totalRecords }} results</p>

        <div class="flex items-center space-x-2">
            <Link v-if="firstPageUrl && currentPage > 1"
                  :href="firstPageUrl"
                  class="text-sm hover:text-white hover:bg-primary-500 dark:text-white px-3 py-1 rounded transition ease-in-out">
                <ChevronDoubleLeftIcon class="size-4" />
            </Link>

            <Link v-if="prevPageUrl"
                  :href="prevPageUrl"
                  class="text-sm hover:text-white hover:bg-primary-500 dark:text-white px-3 py-1 rounded transition ease-in-out">
                <ChevronLeftIcon class="size-4" />
            </Link>

            <span class="text-sm text-neutral-800 dark:text-white">Page {{ currentPage }} of {{ Math.ceil(totalRecords / perPage) }}</span>

            <Link v-if="nextPageUrl"
                  :href="nextPageUrl"
                  class="text-sm hover:text-white hover:bg-primary-500 dark:text-white px-3 py-1 rounded transition ease-in-out">
                <ChevronRightIcon class="size-4" />
            </Link>

            <Link v-if="lastPageUrl && nextPageUrl"
                  :href="lastPageUrl"
                  class="text-sm hover:text-white hover:bg-primary-500 dark:text-white px-3 py-1 rounded transition ease-in-out">
                <ChevronDoubleRightIcon class="size-4" />
            </Link>
        </div>
    </div>
</template>
