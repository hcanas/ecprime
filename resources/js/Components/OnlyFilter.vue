<script setup>
import {ref, watch} from "vue";
import {Listbox, ListboxButton, ListboxOptions, ListboxOption} from "@headlessui/vue";
import {debounce, find, omit} from "lodash";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    field: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
});

const tempOptions = [
    { value: '', label: 'All' },
    ...props.options,
];

const selected = ref(route().params[props.field]
    ? find(props.options, option => option.value === route().params[props.field])
    : tempOptions[0]
);

watch(
    () => selected.value.value,
    debounce(val => {
        const params = omit(route().params, [props.field, 'page']);

        if (Boolean(val)) params[props.field] = val;

        router.visit(route(route().current(), params), {
            preserveState: true,
        }, 300);
    }),
);
</script>

<template>
    <Listbox v-model="selected">
        <ListboxButton>{{ label }}: {{ selected.label }}</ListboxButton>
        <ListboxOptions>
            <ListboxOption
                v-for="option in tempOptions"
                :key="option.value"
                :value="option"
            >
                {{option.label}}
            </ListboxOption>
        </ListboxOptions>
    </Listbox>
</template>
