<script setup>
import {Menu, MenuButton, MenuItems} from "@headlessui/vue";
import {ChevronDownIcon} from "@heroicons/vue/16/solid/index.js";
</script>

<template>
    <div class="relative">
        <Menu>
            <MenuButton
                class="text-neutral-500 hover:text-primary-500 font-medium transition ease-in-out"
            >
                <div class="flex items-center">
                    <span>
                        <slot name="buttonText" />
                    </span>
                    <ChevronDownIcon
                        aria-hidden="true"
                        class="size-5"
                    />
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
                    class="absolute right-0 mt-2 z-50 w-36 flex flex-col origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                >
                    <slot name="items" />
                </MenuItems>
            </transition>
        </Menu>
    </div>
</template>
