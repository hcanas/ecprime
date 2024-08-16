<script setup>
import {onMounted, ref, watch} from "vue";
import {Combobox, ComboboxInput, ComboboxOptions, ComboboxOption, ComboboxButton} from "@headlessui/vue";
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
                class="w-full border-neutral-300 focus:border-primary-300 focus:ring-0 rounded-md z-0"
                :class="{ 'bg-neutral-100 cursor-not-allowed': disabled }"
                @change="query = $event.target.value"
                :disabled="disabled"
            />
            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                <ChevronUpDownIcon class="size-4" />
            </ComboboxButton>
            <ComboboxOptions class="absolute mt-1 py-1 max-h-60 w-full rounded-lg bg-white shadow-lg z-10">
                <ComboboxOption
                    v-for="(item, key) in selection"
                    as="template"
                    :key="key"
                    :value="item"
                    v-slot="{ active }"
                >
                    <li class="relative text-sm px-3 py-1 cursor-pointer text-neutral-800 hover:bg-primary" :class="{ 'bg-primary': active }">
                        {{ item }}
                    </li>
                </ComboboxOption>
            </ComboboxOptions>
        </Combobox>
    </div>
</template>
