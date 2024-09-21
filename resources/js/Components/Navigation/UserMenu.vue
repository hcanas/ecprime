<script setup>
import {Menu, MenuButton, MenuItems} from "@headlessui/vue";
import {ChevronDownIcon} from "@heroicons/vue/16/solid/index.js";
import {usePage} from "@inertiajs/vue3";

const user = usePage().props.auth.user;
</script>

<template>
    <div class="relative">
        <Menu>
            <MenuButton
                class="text-sm text-neutral-400 hover:text-primary-500 dark:hover:text-primary-400 font-medium transition ease-in-out"
            >
                <div class="flex items-center">
                    <span class="w-14 md:w-auto truncate">{{ user.name }}</span>
                    <ChevronDownIcon class="size-5" />
                </div>
            </MenuButton>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <MenuItems
                    class="absolute right-0 mt-4 z-50 w-44 flex flex-col items-center origin-top-right divide-y divide-gray-100 dark:divide-neutral-700 dark:bg-neutral-800 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                >
                    <span class="w-full text-sm text-primary-400 text-center truncate px-3 py-2">{{ user.name }}</span>
                    <slot name="items" />
                </MenuItems>
            </transition>
        </Menu>
    </div>
</template>
