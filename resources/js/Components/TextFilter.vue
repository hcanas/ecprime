<script setup>
import {ref, watch} from "vue";
import {debounce, omit} from "lodash";
import {router} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";

const query = ref(route().params['query'] ?? '');

watch(
    () => query.value,
    debounce(val => {
        const params = omit(route().params, ['query', 'page']);

        if (Boolean(val)) params['query'] = val;

        router.visit(route(route().current(), params), {
            preserveState: true,
        });
    }, 1000),
);
</script>

<template>
    <TextInput v-model="query"
               autofocus
               class="w-96"
               placeholder="Search keyword..."
               type="search" />
</template>
