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
    center: 'text-center',
    right: 'text-right',
};
</script>

<template>
    <table class="w-full table-auto">
        <thead>
        <tr class="border-b">
            <th v-for="col in columns"
                :class="[col.align ? alignment[col.align] : alignment.left]"
                class="px-2">
                {{ col.label }}
            </th>
        </tr>
        </thead>
        <tbody class="divide-y">
        <tr v-for="(row, key) in data"
            class="hover:bg-gray-50">
            <td v-for="col in columns"
                :class="col.align ? alignment[col.align] : alignment.left"
                class="p-2">
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
