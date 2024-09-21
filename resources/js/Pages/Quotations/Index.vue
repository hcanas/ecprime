<script setup>
import {Link} from "@inertiajs/vue3";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import TextFilter from "@/Components/TextFilter.vue";
import CustomTable from "@/Components/CustomTable.vue";
import Pagination from "@/Components/Navigation/Pagination.vue";
import OnlyFilter from "@/Components/OnlyFilter.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHead from "@/Components/PageHead.vue";
import Tag from "@/Components/Tag.vue";
import {useFormatter} from "@/Composables/formatter.js";
import {filter} from "lodash";
import {EyeIcon, PencilIcon} from "@heroicons/vue/16/solid/index.js";

defineProps({
    can: {
        type: Object,
    },
    quotations: {
        type: Object,
        required: true,
    },
});

const {formatCurrency} = useFormatter();

const columns = [
    {label: 'Ref #', field: 'reference_number'},
    {label: 'Customer', field: 'customer'},
    {label: 'Items', field: 'items', align: 'center'},
    {label: 'Amount', field: 'amount', align: 'right'},
    {label: 'Status', field: 'status'},
];

const onlyFilterOptions = [
    {value: 'draft', label: 'Drafts'},
    {value: 'pending', label: 'Pending'},
    {value: 'sent', label: 'Sent'},
    {value: 'confirmed', label: 'Confirmed'},
    {value: 'cancelled', label: 'Cancelled'},
];

const statusTagColors = {
    draft: 'text-indigo-500',
    pending: 'text-yellow-400',
    sent: 'text-cyan-500',
    confirmed: 'text-green-500',
    cancelled: 'text-red-500',
};
</script>

<template>
    <BaseLayout>
        <PageHead title="Quotations">
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
                         :data="quotations.data">
                <template #referenceNumberCol="{ rowData }">
                    <div class="flex flex-col">
                        <span>{{ rowData.reference_number }}</span>

                        <Link v-if="rowData.can.update_quotation"
                              :href="route('quotations.edit', { quotation: rowData.id })"
                              class="text-sm text-blue-500 hover:underline">
                            <div class="flex items-center space-x-1">
                                <PencilIcon class="size-3" />
                                <span>Manage</span>
                            </div>
                        </Link>

                        <Link v-if="rowData.can.view_quotation"
                              :href="route('quotations.show', { quotation: rowData.id })"
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

                <template #itemsCol="{ rowData }">
                    <div class="flex flex-col text-sm">
                        <span class="text-green-500 pr-3">{{ filter(rowData.items, item => item.status === 'available').length }} available</span>
                        <span class="text-red-500">{{ filter(rowData.items, item => item.status !== 'available').length }} unavailable</span>
                    </div>
                </template>

                <template #amountCol="{ rowData }">
                    {{ formatCurrency(rowData.amount) }}
                </template>

                <template #statusCol="{ rowData }">
                    <Tag :class="statusTagColors[rowData.status]">
                        {{ rowData.status }}
                    </Tag>
                </template>
            </CustomTable>

            <Pagination
                :currentPage="quotations.current_page"
                :firstPageUrl="quotations.first_page_url"
                :from="quotations.from"
                :lastPageUrl="quotations.last_page_url"
                :nextPageUrl="quotations.next_page_url"
                :perPage="quotations.per_page"
                :prevPageUrl="quotations.prev_page_url"
                :to="quotations.to"
                :totalRecords="quotations.total"
            />
        </div>
    </BaseLayout>
</template>
