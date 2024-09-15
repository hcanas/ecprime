<script setup>
import {Link} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Tag from "@/Components/Tag.vue";

defineProps({
    quotation: {
        type: Object,
        required: true,
    },
});

const statusTagColors = {
    draft: 'text-neutral-600',
    pending: 'text-yellow-400',
    sent: 'text-cyan-500',
    confirmed: 'text-green-500',
    cancelled: 'text-red-500',
};
</script>

<template>
    <Card class="h-max"
          title="Tracking Details">
        <div class="flex flex-col space-y-3">
            <div class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Reference Number</span>
                <span>{{ quotation.reference_number }}</span>
            </div>
            <div v-if="quotation.purchase_order"
                 class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Purchase Order Reference Number</span>
                <Link :href="route(quotation.purchase_order.status === 'pending' ? 'purchase_orders.edit' : 'purchase_orders.show', { purchase_order: quotation.purchase_order.id })"
                      class="text-blue-500 hover:underline">{{ quotation.purchase_order.reference_number }}
                </Link>
            </div>
            <div class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Status</span>
                <Tag :class="statusTagColors[quotation.status]">{{ quotation.status }}</Tag>
            </div>
        </div>
    </Card>
</template>
