<script setup>
import {ref, watch} from "vue";
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {debounce, find, omit} from "lodash";
import {router} from "@inertiajs/vue3";
import {ChevronDownIcon} from "@heroicons/vue/16/solid/index.js";

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
    includeAll: {
        type: Boolean,
        default: true,
    },
});

const tempOptions = props.options;

if (props.includeAll) {
    tempOptions.unshift({value: '', label: 'All'});
}

const selected = ref(route().params[props.field]
    ? find(props.options, option => option.value === route().params[props.field])
    : tempOptions[0]
);

watch(
    () => selected.value.value,
    debounce(val => {
        const params = omit(route().params, [props.field, 'page']);

        if (Boolean(val)) params[props.field] = val;

        router.visit(route(route().current(), params));
    }, 300),
);
</script>

<template>
    <Listbox v-model="selected">
        <div class="relative">
            <ListboxButton class="hover:text-primary-500 bg-neutral-100 dark:text-white dark:hover:text-primary-400 dark:bg-neutral-700 dark:border-neutral-700 px-3 py-2.5 rounded-md shadow-sm border">
                <div class="flex items-center space-x-1">
                    <span class="text-sm">{{ label }}:</span>
                    <span class="text-sm">{{ selected.label }}</span>
                    <ChevronDownIcon class="size-4" />
                </div>
            </ListboxButton>

            <transition
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <ListboxOptions class="z-50 absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white border dark:text-white dark:bg-neutral-700 dark:border-neutral-700 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                    <ListboxOption
                        v-for="option in tempOptions"
                        :key="option.value"
                        :value="option"
                        class="px-2 py-1 hover:text-primary-500 dark:hover:text-primary-400 cursor-pointer"
                    >
                        {{ option.label }}
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>
