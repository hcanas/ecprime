<script setup>
import {Link} from "@inertiajs/vue3";
import {
    ChevronLeftIcon,
    ChevronDoubleLeftIcon,
    ChevronRightIcon,
    ChevronDoubleRightIcon
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
        required: true,
    },
    nextPageUrl: {
        type: String,
        required: true,
    },
    firstPageUrl: {
        type: String,
        required: true,
    },
    lastPageUrl: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div v-if="totalRecords > perPage" class="w-full md:w-auto flex flex-col md:flex-row items-center justify-between md:space-x-3 md:order-last mt-6">
        <p v-if="totalRecords > 1" class="order-2 text-sm md:order-first">Showing {{ from }} to {{ to }} of {{ totalRecords }} results</p>

        <div class="flex items-center space-x-2">
            <Link v-if="firstPageUrl && currentPage > 1" class="text-sm hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="firstPageUrl">
                <ChevronDoubleLeftIcon class="size-4" />
            </Link>

            <Link v-if="prevPageUrl" class="text-sm hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="prevPageUrl">
                <ChevronLeftIcon class="size-4" />
            </Link>

            <span class="text-sm text-neutral-800">Page {{ currentPage }} of {{ Math.ceil(totalRecords / perPage) }}</span>

            <Link v-if="nextPageUrl" class="text-sm hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="nextPageUrl">
                <ChevronRightIcon class="size-4" />
            </Link>

            <Link v-if="lastPageUrl && nextPageUrl" class="text-sm hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="lastPageUrl">
                <ChevronDoubleRightIcon class="size-4" />
            </Link>
        </div>
    </div>
</template>
