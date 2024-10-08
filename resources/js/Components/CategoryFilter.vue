<script setup>
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {onMounted, ref, watch} from "vue";
import {debounce, find, omit} from "lodash";
import {router} from "@inertiajs/vue3";
import {ChevronDownIcon} from "@heroicons/vue/16/solid/index.js";

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});

const tempOptions = [
    {name: 'All', level: 'main'},
    ...props.options,
];

const selected = ref(tempOptions[0]);

onMounted(() => {
    if (route().params['main_category'] && route().params['sub_category']) {
        selected.value = find(tempOptions, option => {
            return option.parent === route().params['main_category']
                && option.name === route().params['sub_category'];
        });
    } else if (route().params['main_category']) {
        selected.value = find(tempOptions, option => option.name === route().params['main_category']);
    }

    watch(
        () => selected.value,
        debounce(val => {
            const params = omit(route().params, ['main_category', 'sub_category', 'page']);

            if (val.name !== 'All') {
                params['main_category'] = val.level === 'sub' ? val.parent : val.name;

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
        <div class="relative">
            <ListboxButton class="w-full hover:text-primary-500 dark:text-white dark:hover:text-primary-400 dark:bg-neutral-700 dark:border-neutral-700 px-3 py-2.5 rounded-md shadow-sm border">
                <div class="flex items-center gap-x-1">
                    <span class="text-sm">Category:</span>
                    <span class="flex-grow text-left text-sm truncate">{{ selected?.name + (selected?.parent ? '(' + selected.parent + ')' : '') }}</span>
                    <ChevronDownIcon class="size-4" />
                </div>
            </ListboxButton>

            <transition
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <ListboxOptions class="z-50 absolute md:right-0 mt-1 max-h-60 w-full md:w-max overflow-auto rounded-md bg-white border dark:text-white dark:bg-neutral-700 dark:border-neutral-700 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                    <ListboxOption
                        v-for="option in tempOptions"
                        :key="option.name"
                        :class="{ 'pl-6': option.level === 'sub' }"
                        :value="option"
                        class="px-2 py-2 hover:text-primary-500 dark:hover:text-primary-400 cursor-pointer"
                    >
                        {{ option.name }}
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>
