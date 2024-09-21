<script setup>
import {Link} from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Tag from "@/Components/Tag.vue";

defineProps({
    purchaseOrder: {
        type: Object,
        required: true,
    },
});

const statusTagColors = {
    pending: 'text-yellow-400',
    delivered: 'text-green-500',
    cancelled: 'text-red-500',
};
</script>

<template>
    <Card class="xl:h-max"
          title="Tracking Details">
        <div class="flex flex-col space-y-3">
            <p class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Reference Number</span>
                <span>{{ purchaseOrder.reference_number }}</span>
            </p>
            <p class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Quotation Reference Number</span>
                <Link :href="route('quotations.show', { quotation: purchaseOrder.quotation_id })"
                      class="text-blue-500 hover:underline">{{ purchaseOrder.quotation_reference_number }}
                </Link>
            </p>
            <p class="flex flex-col">
                <span class="text-xs text-neutral-500 uppercase font-medium">Status</span>
                <Tag :class="statusTagColors[purchaseOrder.status]">{{ purchaseOrder.status }}</Tag>
            </p>
        </div>
    </Card>
</template>
