<script setup>
import {Link} from "@inertiajs/vue3";
import {useFormatter} from "@/Composables/formatter.js";

const {formatDateTime} = useFormatter();

defineProps({
    user: {
        type: Object,
    },
    dateTime: {
        type: String,
    },
});
</script>

<template>
    <div v-if="user"
         class="flex items-center space-x-1 flex-wrap text-xs text-neutral-500 italic">
        <span>Last modified by</span>
        <span v-if="user.id === $page.props.auth.user.id">you</span>
        <Link v-else
              :href="route('users.edit', { user: user.id })"
              class="text-blue-500 hover:underline">
            {{ user.name }}
        </Link>
        <span>on {{ formatDateTime(dateTime) }}.</span>
    </div>
</template>
