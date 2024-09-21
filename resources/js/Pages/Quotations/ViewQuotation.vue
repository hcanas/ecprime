<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import CustomTable from "@/Components/CustomTable.vue";
import {ref} from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {reduce} from "lodash/collection.js";
import PageHead from "@/Components/PageHead.vue";
import Card from "@/Components/Card.vue";
import {useFormatter} from "@/Composables/formatter.js";
import LastModifiedBy from "@/Components/LastModifiedBy.vue";
import CustomerInformation from "@/Pages/Quotations/Partials/CustomerInformation.vue";
import TrackingDetails from "@/Pages/Quotations/Partials/TrackingDetails.vue";

const props = defineProps({
    quotation: {
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


const itemsDraft = ref(props.quotation.items);

const totalAmount = reduce(props.quotation.items, (t, item) => {
    return item.status === 'available'
        ? t + (item.quantity * item.price)
        : t;
}, 0);
</script>

<template>
    <BaseLayout>
        <PageHead title="Quotation Details" />

        <FlashMessage />

        <div class="flex flex-col xl:grid xl:grid-cols-4 xl:gap-x-6 gap-y-3">
            <div class="flex flex-col md:grid md:grid-cols-2 md:gap-x-6 xl:flex xl:flex-col gap-y-6">
                <CustomerInformation :customer="quotation.customer" />
                <TrackingDetails :quotation="quotation" />
            </div>

            <div class="xl:col-span-3 flex flex-col gap-y-6">
                <Card class="h-max"
                      title="Items">
                    <CustomTable :columns="columns"
                                 :data="itemsDraft">
                        <template #nameCol="{ rowData, index }">
                            <div class="flex flex-col">
                                <img :src="rowData.image ? `/storage/images/${rowData.image}` : '/images/placeholder.svg'"
                                     alt="Image"
                                     class="size-60 xl:size-10" />
                                <span>{{ rowData.name }}</span>
                                <span class="text-sm">{{ rowData.description }}</span>
                            </div>
                        </template>

                        <template #brandCol="{ rowData, index }">
                            <span>{{ rowData.brand ?? 'No Brand' }}</span>
                        </template>

                        <template #quantityCol="{ rowData, index }">
                            <span>{{ formatNumber(rowData.quantity) }}</span>
                        </template>

                        <template #priceCol="{ rowData, index }">
                            <span>{{ formatCurrency(rowData.price) }}</span>
                        </template>

                        <template #totalCol="{ rowData }">
                            {{ formatCurrency(rowData.status === 'available' ? rowData.quantity * rowData.price : 0) }}
                        </template>
                    </CustomTable>

                    <p class="text-right font-medium pt-3">{{ formatCurrency(totalAmount) }}</p>
                </Card>

                <LastModifiedBy :dateTime="quotation.updated_at"
                                :user="quotation.last_modified_by" />
            </div>
        </div>
    </BaseLayout>
</template>
