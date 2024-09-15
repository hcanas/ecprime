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
            <ListboxButton class="px-3 py-2.5 rounded-md shadow-sm border hover:text-primary-500">
                <div class="flex items-center space-x-1">
                    <span class="text-sm">Category:</span>
                    <span class="text-sm">{{ selected?.name + (selected?.parent ? '(' + selected.parent + ')' : '') }}</span>
                    <ChevronDownIcon class="size-4" />
                </div>
            </ListboxButton>

            <transition
                leave-active-class="transition duration-100 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <ListboxOptions class="z-50 absolute mt-1 max-h-60 w-max overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm">
                    <ListboxOption
                        v-for="option in tempOptions"
                        :key="option.name"
                        :class="{ 'pl-6': option.level === 'sub' }"
                        :value="option"
                        class="px-2 py-1 hover:text-primary-500 cursor-pointer"
                    >
                        {{ option.name }}
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>
