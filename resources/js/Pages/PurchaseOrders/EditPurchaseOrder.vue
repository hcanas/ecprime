<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import CustomTable from "@/Components/CustomTable.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import {computed} from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {map, reduce} from "lodash/collection.js";
import PageHead from "@/Components/PageHead.vue";
import Card from "@/Components/Card.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {useFormatter} from "@/Composables/formatter.js";
import SuccessButton from "@/Components/SuccessButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import LastModifiedBy from "@/Components/LastModifiedBy.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomerInformation from "@/Pages/Quotations/Partials/CustomerInformation.vue";
import TrackingDetails from "@/Pages/PurchaseOrders/Partials/TrackingDetails.vue";

const props = defineProps({
    purchase_order: {
        type: Object,
        required: true,
    },
});

const {formatCurrency} = useFormatter();

const columns = [
    {label: 'Product', field: 'name'},
    {label: 'Brand', field: 'brand'},
    {label: 'Quantity', field: 'quantity', align: 'right'},
    {label: 'Unit', field: 'measurement_unit'},
    {label: 'Price', field: 'price', align: 'right'},
    {label: 'Total', field: 'total', align: 'right'},
];

const form = useForm('put', route('purchase_orders.update', {purchase_order: props.purchase_order.id}), {
    // replace null with empty string for form.isDirty to work properly
    items: map(props.purchase_order.items, item => ({
        ...item,
        brand: item.brand ?? '',
    })),
    payment_details: props.purchase_order.payment_details ?? '',
    delivery_date: props.purchase_order.delivery_date ?? '',
    status: props.purchase_order.status,
});

const totalAmount = computed(() => reduce(form.items, (t, item) => {
    return t + (item.quantity * item.price);
}, 0));

function saveChanges() {
    if (!form.isDirty) {
        alert('There are no changes.');
        return;
    }

    form.submit();
}

function confirmDelivery() {
    if (confirm('This action is irreversible. Confirming delivery.')) {
        form.status = 'delivered';
        form.submit();
    }
}

function cancelPurchaseOrder() {
    if (confirm('This action is irreversible. Cancelling purchase order.')) {
        form.status = 'cancelled';
        form.submit();
    }
}
</script>

<template>
    <BaseLayout>
        <PageHead title="Edit Purchase Order" />

        <FlashMessage />

        <div class="flex flex-col xl:grid xl:grid-cols-4 xl:gap-x-6 gap-y-3">
            <div class="flex flex-col md:grid md:grid-cols-2 md:gap-x-6 xl:flex xl:flex-col gap-y-6">
                <CustomerInformation :customer="purchase_order.customer" />

                <TrackingDetails :purchaseOrder="purchase_order" />

                <div class="flex flex-col gap-y-6 border border-green-200 dark:border-neutral-800 dark:bg-neutral-800 rounded-md p-3 shadow">
                    <div class="flex flex-col text-sm">
                        <p class="font-medium">Delivery Confirmation</p>
                        <p class="text-neutral-500">Confirming delivery will lock this record to prevent further modification. This action is irreversible.</p>
                    </div>
                    <SuccessButton v-if="purchase_order.payment_details && purchase_order.delivery_date"
                                   class="flex-shrink-0"
                                   @click="confirmDelivery">Confirm Delivery
                    </SuccessButton>
                    <span v-else
                          class="text-red-500 text-sm italic">*** Payment details and delivery date must be set first.</span>
                </div>

                <div class="flex flex-col gap-y-6 border border-red-200 dark:border-neutral-800 dark:bg-neutral-800 rounded-md p-3 shadow">
                    <div class="flex flex-col text-sm">
                        <p class="font-medium">Cancellation</p>
                        <p class="text-neutral-500">Cancelling this purchase order will lock the record to prevent further modification. This action is irreversible.</p>
                    </div>
                    <DangerButton class="flex-shrink-0"
                                  @click="cancelPurchaseOrder">Cancel Purchase Order
                    </DangerButton>
                </div>
            </div>

            <form class="col-span-3"
                  @submit.prevent>
                <div class="flex flex-col gap-y-6">
                    <Card class="h-max"
                          title="Items">
                        <CustomTable v-if="form.items"
                                     :columns="columns"
                                     :data="form.items">
                            <template #nameCol="{ rowData, index }">
                                <div class="flex flex-col">
                                    <img :src="rowData.image ? `/storage/images/${rowData.image}` : '/images/placeholder.svg'"
                                         alt="Image"
                                         class="size-60 xl:size-14" />
                                    <span>{{ rowData.name }}</span>
                                    <span class="text-sm">{{ rowData.description }}</span>
                                    <InputError :message="form.errors[`items.${index}.id`]" />
                                </div>
                            </template>

                            <template #brandCol="{ rowData, index }">
                                <div class="flex flex-col">
                                    <TextInput v-model="form.items[index].brand" />
                                    <InputError :message="form.errors[`items.${index}.brand`]" />
                                </div>
                            </template>

                            <template #quantityCol="{ rowData, index }">
                                <div class="flex flex-col">
                                    <TextInput v-model="form.items[index].quantity"
                                               class="xl:w-20 xl:text-right"
                                               min="0"
                                               required
                                               type="number"
                                               @change="form.validate(`items.${index}.quantity`)" />
                                    <InputError :message="form.errors[`items.${index}.quantity`]" />
                                </div>
                            </template>

                            <template #measurementUnitCol="{ rowData, index }">
                                <div class="flex flex-col">
                                    <TextInput v-model="form.items[index].measurement_unit"
                                               class="xl:w-32"
                                               required
                                               @change="form.validate(`items.${index}.measurement_unit`)" />
                                    <InputError :message="form.errors[`items.${index}.measurement_unit`]" />
                                </div>
                            </template>

                            <template #priceCol="{ rowData, index }">
                                <div class="flex flex-col">
                                    <TextInput v-model="form.items[index].price"
                                               class="xl:w-36 xl:text-right"
                                               min="0"
                                               required
                                               type="number"
                                               @change="form.validate(`items.${index}.price`)" />
                                    <InputError :message="form.errors[`items.${index}.price`]" />
                                </div>
                            </template>

                            <template #totalCol="{ rowData }">
                                {{ formatCurrency(rowData.quantity * rowData.price) }}
                            </template>
                        </CustomTable>

                        <p class="text-right font-medium pt-3">{{ formatCurrency(totalAmount) }}</p>
                    </Card>

                    <div class="flex flex-col md:grid md:grid-cols-2 md:gap-x-6 gap-y-3">
                        <Card title="Payment">
                            <div class="flex flex-col">
                                <InputLabel>Payment Details</InputLabel>
                                <TextInput v-model="form.payment_details"
                                           @change="form.validate('payment_details')" />
                                <InputError :message="form.errors['payment_details']" />
                            </div>
                        </Card>

                        <Card title="Delivery">
                            <div class="flex flex-col">
                                <InputLabel>Delivery Date</InputLabel>
                                <TextInput v-model="form.delivery_date"
                                           type="date"
                                           class="w-full"
                                           @change="form.validate('delivery_date')" />
                                <InputError :message="form.errors['delivery_date']" />
                            </div>
                        </Card>
                    </div>

                    <div class="flex flex-col md:flex-row flex-col-reverse md:flex-row-reverse md:items-start md:justify-between gap-y-3">
                        <div class="flex-shrink-0 flex flex-col md:flex-row md:gap-x-3 gap-y-3">
                            <DangerButton v-if="form.isDirty"
                                          type="button"
                                          @click="form.reset()">
                                Discard Changes
                            </DangerButton>

                            <PrimaryButton :disabled="form.processing"
                                           @click="saveChanges">
                                Save Changes
                            </PrimaryButton>
                        </div>

                        <div class="flex flex-col">
                            <LastModifiedBy :dateTime="purchase_order.updated_at"
                                            :user="purchase_order.last_modified_by" />
                            <p v-if="form.isDirty"
                               class="text-xs text-red-500 dark:text-red-500 italic">*** Any unsaved changes will be discarded.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </BaseLayout>
</template>

<style scoped>
input {
    @apply px-3 py-2 bg-neutral-100 border-gray-300 rounded-md shadow-sm outline-none focus:ring-0
}
</style>
