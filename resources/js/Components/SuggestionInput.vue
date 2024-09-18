<script setup>
import {onMounted, ref, watch} from "vue";
import {Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions} from "@headlessui/vue";
import {debounce} from "lodash";
import {ChevronUpDownIcon} from "@heroicons/vue/16/solid/index.js";

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    src: {
        type: String,
        required: true,
    },
    disabled: {
        type: String,
    },
});

const query = ref('');
const selection = ref([]);

watch(query, () => {
    selection.value = [];
    model.value = query.value;
});

watch(
    () => query.value,
    debounce(val => {
        if (val !== '') {
            axios.get(props.src + '?query=' + val)
                .then(response => selection.value = response.data);
        }
    }, 300),
);

onMounted(() => {
    axios.get(props.src)
        .then(response => selection.value = response.data);
});
</script>

<template>
    <div class="relative">
        <Combobox v-model="model">
            <ComboboxInput
                :class="{ 'bg-neutral-100 cursor-not-allowed': disabled }"
                :disabled="disabled"
                class="w-full bg-neutral-100 border-neutral-300 dark:border-neutral-700 dark:bg-neutral-700 focus:ring-0 rounded-md z-0"
                @change="query = $event.target.value"
            />
            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                <ChevronUpDownIcon class="size-4 dark:text-neutral-100" />
            </ComboboxButton>
            <ComboboxOptions class="absolute mt-1 py-1 max-h-60 w-full rounded-md bg-white dark:bg-neutral-700 shadow-lg z-10">
                <ComboboxOption
                    v-for="(item, key) in selection"
                    :key="key"
                    v-slot="{ active }"
                    :value="item"
                    as="template"
                >
                    <li :class="{ 'bg-primary': active }"
                        class="relative text-sm px-3 py-1 cursor-pointer text-neutral-950/95 hover:text-primary-500 dark:text-neutral-100 dark:hover:text-primary-400">
                        {{ item }}
                    </li>
                </ComboboxOption>
            </ComboboxOptions>
        </Combobox>
    </div>
</template>
