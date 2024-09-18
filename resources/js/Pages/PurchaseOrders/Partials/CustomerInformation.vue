<script setup>
import Card from "@/Components/Card.vue";

defineProps({
    customer: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Card class="h-max"
          title="Customer Information">
        <div class="flex flex-col space-y-3">
            <p class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Email</span>
                <span>{{ customer.email }}</span>
            </p>
            <p class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Contact Number</span>
                <span>{{ customer.contact_number }}</span>
            </p>
            <p class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Viber ID</span>
                <span>{{ customer.viber_id ?? 'N/A' }}</span>
            </p>
        </div>
    </Card>
</template>
