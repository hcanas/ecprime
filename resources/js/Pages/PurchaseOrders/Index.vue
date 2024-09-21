<script setup>
import {Link} from "@inertiajs/vue3";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import OnlyFilter from "@/Components/OnlyFilter.vue";
import CustomTable from "@/Components/CustomTable.vue";
import Pagination from "@/Components/Navigation/Pagination.vue";
import TextFilter from "@/Components/TextFilter.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHead from "@/Components/PageHead.vue";
import {EyeIcon, PencilIcon} from "@heroicons/vue/16/solid/index.js";
import {useFormatter} from "@/Composables/formatter.js";
import Tag from "@/Components/Tag.vue";

defineProps({
    purchase_orders: {
        type: Object,
        required: true,
    },
});

const {formatCurrency} = useFormatter();

const columns = [
    {label: 'Ref #', field: 'reference_number'},
    {label: 'Customer', field: 'customer'},
    {label: 'Quotation Ref #', field: 'quotation_reference_number'},
    {label: 'Items', field: 'items_count', align: 'right'},
    {label: 'Amount', field: 'amount', align: 'right'},
    {label: 'Payment', field: 'payment_details'},
    {label: 'Delivery Date', field: 'delivery_date'},
    {label: 'Status', field: 'status'},
];

const onlyFilterOptions = [
    {value: 'pending', label: 'Pending'},
    {value: 'delivered', label: 'Delivered'},
    {value: 'cancelled', label: 'Cancelled'},
];

const statusTagColors = {
    pending: 'text-yellow-400',
    delivered: 'text-green-500',
    cancelled: 'text-red-500',
};
</script>

<template>
    <BaseLayout>
        <PageHead title="Purchase Orders">
            <template #filters>
                <TextFilter />
                <OnlyFilter :options="onlyFilterOptions"
                            field="status"
                            label="Status" />
            </template>
        </PageHead>

        <div class="flex flex-col">
            <FlashMessage />

            <CustomTable :columns="columns"
                         :data="purchase_orders.data">
                <template #referenceNumberCol="{ rowData }">
                    <div class="flex flex-col">
                        <span>{{ rowData.reference_number }}</span>

                        <Link v-if="rowData.can.update_purchase_order"
                              :href="route('purchase_orders.edit', { purchase_order: rowData.id })"
                              class="text-sm text-blue-500 hover:underline">
                            <div class="flex items-center space-x-1">
                                <PencilIcon class="size-3" />
                                <span>Manage</span>
                            </div>
                        </Link>

                        <Link v-if="rowData.can.view_purchase_order"
                              :href="route('purchase_orders.show', { purchase_order: rowData.id })"
                              class="text-sm text-blue-500 hover:underline">
                            <div class="flex items-center space-x-1">
                                <EyeIcon class="size-3" />
                                <span>View</span>
                            </div>
                        </Link>
                    </div>
                </template>

                <template #customerCol="{ rowData }">
                    <div class="flex flex-col">
                        <span>{{ rowData.customer.email }}</span>
                        <span>{{ rowData.customer.contact_number }}</span>
                        <span>{{ rowData.customer.viber_id }}</span>
                    </div>
                </template>

                <template #amountCol="{ rowData }">
                    {{ formatCurrency(rowData.amount) }}
                </template>

                <template #statusCol="{ rowData }">
                    <Tag :class="statusTagColors[rowData.status]">{{ rowData.status }}</Tag>
                </template>
            </CustomTable>

            <Pagination
                :currentPage="purchase_orders.current_page"
                :firstPageUrl="purchase_orders.first_page_url"
                :from="purchase_orders.from"
                :lastPageUrl="purchase_orders.last_page_url"
                :nextPageUrl="purchase_orders.next_page_url"
                :perPage="purchase_orders.per_page"
                :prevPageUrl="purchase_orders.prev_page_url"
                :to="purchase_orders.to"
                :totalRecords="purchase_orders.total"
            />
        </div>
    </BaseLayout>
</template>
