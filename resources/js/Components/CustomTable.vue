<script setup>
import {camelCase} from "lodash";

defineProps({
    data: {
        type: Object,
        required: true,
    },
    columns: {
        type: Array,
        required: true,
    },
});

const alignment = {
    left: 'text-left',
    center: 'text-left xl:text-center',
    right: 'text-left xl:text-right',
};
</script>

<template>
    <table class="w-full table-auto">
        <thead>
        <tr class="hidden xl:table-row border-b dark:border-neutral-700">
            <th v-for="col in columns"
                :class="[col.align ? alignment[col.align] : alignment.left]"
                class="xl:table-cell p-2">
                {{ col.label }}
            </th>
        </tr>
        </thead>
        <tbody class="md:grid md:grid-cols-2 md:gap-x-3 xl:table-row-group xl:divide-y dark:xl:divide-neutral-700">
        <tr v-for="(row, key) in data"
            class="flex flex-col gap-y-3 md:table-row mb-3 p-2 border xl:border-0 dark:border-neutral-800 rounded-md bg-neutral-100 xl:bg-transparent dark:bg-neutral-900 dark:xl:bg-transparent shadow xl:shadow-none xl:hover:bg-gray-50 dark:xl:hover:bg-neutral-800">
            <td v-for="col in columns"
                :class="col.align ? alignment[col.align] : alignment.left"
                class="flex flex-col xl:table-cell xl:p-2">
                <span class="text-xs text-neutral-400 uppercase font-medium xl:hidden">{{ col.label }}</span>
                <slot :index="key"
                      :name="camelCase(col.field) + 'Col'"
                      :rowData="row">
                    {{ row[col.field] }}
                </slot>
            </td>
        </tr>
        </tbody>
    </table>
</template>
