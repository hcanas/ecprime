<script setup>
import {Link} from "@inertiajs/vue3";
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
</script>

<template>
    <table>
        <thead>
            <tr>
                <th v-for="col in columns">{{col.label}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in data">
                <td v-for="col in columns">
                    <slot :name="camelCase(col.field) + 'Col'" :rowData="row">
                        {{row[col.field]}}
                    </slot>
                </td>
            </tr>
        </tbody>
    </table>
</template>
