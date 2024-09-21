<script setup>
import {ref, watch} from 'vue';
import {Switch, SwitchGroup, SwitchLabel} from '@headlessui/vue';
import {MoonIcon, SunIcon} from "@heroicons/vue/16/solid/index.js";

const enabled = ref(localStorage.theme === 'dark');

watch(() => enabled.value, () => {
    if (enabled.value) {
        localStorage.theme = 'dark';
        document.documentElement.classList.add('dark');

        dispatchEvent(new StorageEvent('storage', {
            key: 'theme',
            newValue: 'dark',
        }));
    } else {
        localStorage.theme = 'light';
        document.documentElement.classList.remove('dark');

        dispatchEvent(new StorageEvent('storage', {
            key: 'theme',
            newValue: 'light',
        }));
    }
});
</script>

<template>
    <SwitchGroup>
        <div class="md:flex md:items-center md:space-x-1">
            <SwitchLabel :class="{ 'hidden md:inline': !enabled }" title="Light Mode">
                <SunIcon class="size-5 text-yellow-400" />
            </SwitchLabel>
            <Switch
                v-model="enabled"
                :class="enabled ? 'bg-neutral-700' : 'bg-neutral-200'"
                class="hidden relative md:inline-flex h-4 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
            >
                <span
                    :class="enabled ? 'translate-x-6' : 'translate-x-0'"
                    aria-hidden="true"
                    class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                />
            </Switch>
            <SwitchLabel :class="{ 'hidden md:inline': enabled }" title="Dark Mode">
                <MoonIcon class="size-5 text-purple-600" />
            </SwitchLabel>
        </div>
    </SwitchGroup>
</template>
