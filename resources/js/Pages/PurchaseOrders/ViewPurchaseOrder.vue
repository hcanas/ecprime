<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import CustomTable from "@/Components/CustomTable.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {reduce} from "lodash/collection.js";
import PageHead from "@/Components/PageHead.vue";
import Card from "@/Components/Card.vue";
import {useFormatter} from "@/Composables/formatter.js";
import LastModifiedBy from "@/Components/LastModifiedBy.vue";
import CustomerInformation from "@/Pages/Quotations/Partials/CustomerInformation.vue";
import TrackingDetails from "@/Pages/PurchaseOrders/Partials/TrackingDetails.vue";

const props = defineProps({
    purchase_order: {
        type: Object,
        required: true,
    },
});

const {formatCurrency, formatNumber} = useFormatter();

const columns = [
    {label: 'Product', field: 'name'},
    {label: 'Brand', field: 'brand'},
    {label: 'Quantity', field: 'quantity', align: 'right'},
    {label: 'Unit', field: 'measurement_unit'},
    {label: 'Price', field: 'price', align: 'right'},
    {label: 'Total', field: 'total', align: 'right'},
];

const totalAmount = reduce(props.purchase_order.items, (t, item) => {
    return t + (item.quantity * item.price);
}, 0);
</script>

<template>
    <BaseLayout>
        <PageHead title="Purchase Order Details" />

        <FlashMessage />

        <div class="grid grid-cols-4 gap-6">
            <div class="flex flex-col space-y-6">
                <CustomerInformation :customer="purchase_order.customer" />
                <TrackingDetails :purchaseOrder="purchase_order" />
            </div>

            <div class="col-span-3 flex flex-col space-y-6">
                <Card class="h-max"
                      title="Items">
                    <CustomTable v-if="purchase_order.items"
                                 :columns="columns"
                                 :data="purchase_order.items">
                        <template #nameCol="{ rowData, index }">
                            <div class="flex flex-col">
                                <img :src="rowData.image ? `/storage/images/${rowData.image}` : '/images/placeholder.png'"
                                     alt="Image"
                                     class="size-10" />
                                <span>{{ rowData.name }}</span>
                                <span class="text-sm">{{ rowData.description }}</span>
                            </div>
                        </template>

                        <template #quantityCol="{ rowData, index }">
                            <span>{{ formatNumber(rowData.quantity) }}</span>
                        </template>

                        <template #priceCol="{ rowData, index }">
                            <span>{{ formatCurrency(rowData.price) }}</span>
                        </template>

                        <template #totalCol="{ rowData }">
                            {{ formatCurrency(rowData.quantity * rowData.price) }}
                        </template>
                    </CustomTable>

                    <p class="text-right font-medium border-t pt-3">{{ formatCurrency(totalAmount) }}</p>
                </Card>

                <div class="grid grid-cols-2 gap-6">
                    <Card title="Payment Details">
                        <p class="text-sm italic">{{ purchase_order.payment_details ?? 'Not Specified' }}</p>
                    </Card>

                    <Card title="Delivery Date">
                        <p class="text-sm italic">{{ purchase_order.delivery_date ?? 'Not Specified' }}</p>
                    </Card>
                </div>

                <LastModifiedBy :dateTime="purchase_order.updated_at"
                                :user="purchase_order.last_modified_by" />
            </div>
        </div>
    </BaseLayout>
</template>
