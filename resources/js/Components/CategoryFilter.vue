<script setup>
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {onMounted, ref, watch} from "vue";
import {debounce, find, omit} from "lodash";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});

const tempOptions = [
    { name: 'All', level: 'main' },
    ...props.options,
];

const selected = ref();

onMounted(() => {
    if (route().params['category'] && route().params['sub_category']) {
        selected.value = find(tempOptions, option => {
            return option.parent === route().params['category']
                && option.name === route().params['sub_category'];
        });
    } else if (route().params['category']) {
        selected.value = find(tempOptions, option => option.name === route().params['category']);
    } else {
        selected.value = tempOptions[0];
    }

    watch(
        () => selected.value,
        debounce(val => {
            const params = omit(route().params, ['category', 'sub_category', 'page']);

            if (val.name !== 'All') {
                params['category'] = val.level === 'sub' ? val.parent : val.name;

                if (val.level === 'sub') params['sub_category'] = val.name;
            }

            router.visit(route(route().current(), params), {
                preserveState: true,
            });
        }, 300),
    );
});
</script>

<template>
    <Listbox v-model="selected">
        <ListboxButton>Category: {{ selected?.name + (selected?.parent ? '(' + selected.parent + ')' : '') }}</ListboxButton>
        <ListboxOptions>
            <ListboxOption
                v-for="option in tempOptions"
                :key="option.name"
                :value="option"
                :class="{ 'pl-3': option.level === 'sub' }"
            >
                {{option.name}}
            </ListboxOption>
        </ListboxOptions>
    </Listbox>
</template>
