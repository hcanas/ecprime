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
import {CheckIcon, XMarkIcon} from "@heroicons/vue/16/solid/index.js";
import {useFormatter} from "@/Composables/formatter.js";
import SuccessButton from "@/Components/SuccessButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import LastModifiedBy from "@/Components/LastModifiedBy.vue";
import CustomerInformation from "@/Pages/Quotations/Partials/CustomerInformation.vue";
import TextInput from "@/Components/TextInput.vue";
import TrackingDetails from "@/Pages/Quotations/Partials/TrackingDetails.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    quotation: {
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

const form = useForm('put', route('quotations.update', {quotation: props.quotation.id}), {
    // replace null with empty string for form.isDirty to work properly
    items: map(props.quotation.items, item => ({
        ...item,
        brand: item.brand ?? '',
    })),
    status: '',
});

const totalAmount = computed(() => reduce(form.items, (t, item) => {
    return item.status === 'available'
        ? t + (item.quantity * item.price)
        : t;
}, 0));

function saveChanges() {
    if (!form.isDirty) {
        alert('There are no changes.');
        return;
    }

    form.transform((data) => ({
        items: data.items,
        status: 'draft',
    })).submit();
}

function requestApproval() {
    if (form.isDirty) {
        alert('Please save any changes before proceeding.');
        return;
    }

    form.transform(() => ({status: 'pending'})).submit();
}

function sendQuotation() {
    if (form.isDirty) {
        alert('Please save any changes before proceeding.');
        return;
    }

    form.transform(() => ({status: 'sent'})).submit();
}

function createPurchaseOrder() {
    if (form.isDirty) {
        alert('Please save any changes before proceeding.');
        return;
    }

    if (!confirm('This record will be locked and the purchase order will be created.')) return;

    form.transform(() => ({status: 'confirmed'})).submit();
}

function cancelQuotation() {
    if (form.isDirty) {
        alert('Please save any changes before proceeding.');
        return;
    }

    if (!confirm('This quotation request will be cancelled.')) return;

    form.transform(() => ({status: 'cancelled'})).submit();
}
</script>

<template>
    <BaseLayout>
        <PageHead title="Edit Quotation" />

        <FlashMessage />

        <div class="grid grid-cols-4 gap-6">
            <div class="flex flex-col space-y-6">
                <CustomerInformation :customer="quotation.customer" />
                <TrackingDetails :quotation="quotation" />

                <div class="flex flex-col space-y-6 border border-green-200 rounded-md p-3">
                    <div class="flex flex-col text-sm">
                        <p class="font-medium">Purchase Order</p>
                        <p class="text-neutral-500">Creating a purchase order will lock this record to prevent further modification. This action is irreversible.</p>
                    </div>
                    <SuccessButton v-if="quotation.status === 'sent'"
                                   class="flex-shrink-0"
                                   @click="createPurchaseOrder">Create Purchase Order
                    </SuccessButton>
                    <span v-else
                          class="text-sm text-red-500 italic">*** Send the quotation first to the customer for confirmation.</span>
                </div>

                <div class="flex flex-col space-y-6 border border-red-200 rounded-md p-3">
                    <div class="flex flex-col text-sm">
                        <p class="font-medium">Cancellation</p>
                        <p class="text-neutral-500">Cancelling this quotation will lock the record to prevent further modification. This action is irreversible.</p>
                    </div>
                    <DangerButton class="flex-shrink-0"
                                  @click="cancelQuotation">Cancel Quotation
                    </DangerButton>
                </div>
            </div>
            <form class="col-span-3"
                  @submit.prevent>
                <div class="flex flex-col space-y-6">
                    <Card class="h-max"
                          title="Items">
                        <CustomTable :columns="columns"
                                     :data="form.items">
                            <template #nameCol="{ rowData, index }">
                                <div class="flex flex-col">
                                    <img :src="rowData.image ? `/storage/images/${rowData.image}` : '/images/placeholder.png'"
                                         alt="Image"
                                         class="size-14" />
                                    <span>{{ rowData.name }}</span>
                                    <span class="text-sm">{{ rowData.description }}</span>
                                    <span
                                        v-if="form.invalid(`items.${index}.name`)">{{ form.errors[`items.${index}.name`] }}</span>

                                    <button v-if="rowData.status !== 'unavailable'"
                                            class="w-max text-sm text-red-500 hover:underline"
                                            type="button"
                                            @click="form.items[index].status = 'unavailable'">
                                        <div class="flex items-center space-x-1">
                                            <XMarkIcon class="size-3" />
                                            <span>Mark Unavailable</span>
                                        </div>
                                    </button>

                                    <button v-else
                                            class="w-max text-sm text-green-500 hover:underline"
                                            type="button"
                                            @click="form.items[index].status = 'available'">
                                        <div class="flex items-center space-x-1">
                                            <CheckIcon class="size-3" />
                                            <span>Mark Available</span>
                                        </div>
                                    </button>
                                </div>
                            </template>

                            <template #brandCol="{ rowData, index }">
                                <span v-if="rowData.status === 'unavailable'">{{ rowData.brand }}</span>
                                <div v-else
                                     class="flex flex-col">
                                    <TextInput v-model="form.items[index].brand"
                                               @change="form.validate(`items.${index}.brand`)" />
                                    <InputError :message="form.errors[`items.${index}.brand`]" />
                                </div>
                            </template>

                            <template #quantityCol="{ rowData, index }">
                                <span v-if="rowData.status === 'unavailable'">{{ rowData.quantity }}</span>
                                <div v-else
                                     class="flex flex-col">
                                    <TextInput v-model="form.items[index].quantity"
                                               class="w-20 text-right"
                                               min="1"
                                               required
                                               type="number"
                                               @change="form.validate(`items.${index}.quantity`)" />
                                    <InputError :message="form.errors[`items.${index}.quantity`]" />
                                </div>
                            </template>

                            <template #measurementUnitCol="{ rowData, index }">
                                <span v-if="rowData.status === 'unavailable'">{{ rowData.measurement_unit }}</span>
                                <div v-else
                                     class="flex flex-col">
                                    <TextInput v-model="form.items[index].measurement_unit"
                                               class="w-32"
                                               required
                                               @change="form.validate(`items.${index}.measurement_unit`)" />
                                    <InputError :message="form.errors[`items.${index}.measurement_unit`]" />
                                </div>
                            </template>

                            <template #priceCol="{ rowData, index }">
                                <span v-if="rowData.status === 'unavailable'">{{ formatCurrency(rowData.price) }}</span>
                                <div v-else
                                     class="flex flex-col">
                                    <TextInput v-model="form.items[index].price"
                                               class="w-36 text-right"
                                               min="0"
                                               required
                                               type="number"
                                               @change="form.validate(`items.${index}.price`)" />
                                    <InputError :message="form.errors[`items.${index}.price`]" />
                                </div>
                            </template>

                            <template #totalCol="{ rowData }">
                                {{ formatCurrency(rowData.status === 'available' ? rowData.quantity * rowData.price : 0) }}
                            </template>
                        </CustomTable>

                        <p class="text-right font-medium border-t pt-3">{{ formatCurrency(totalAmount) }}</p>
                    </Card>

                    <div class="flex flex-row-reverse items-start justify-between">
                        <div class="flex items-center space-x-1">
                            <DangerButton v-if="form.isDirty"
                                          type="button"
                                          @click="form.reset()">Discard Changes
                            </DangerButton>

                            <SecondaryButton :disabled="form.processing"
                                             @click="saveChanges">Save Changes
                            </SecondaryButton>

                            <PrimaryButton v-if="totalAmount > 0 && $page.props.auth.user.role !== 'admin' && quotation.status === 'draft'"
                                           :disabled="form.processing"
                                           @click="requestApproval">Request Approval
                            </PrimaryButton>

                            <PrimaryButton v-if="totalAmount > 0 && $page.props.auth.user.role === 'admin'"
                                           :disabled="form.processing"
                                           @click="sendQuotation">{{ quotation.status === 'pending' ? 'Approve and Send Quotation' : 'Send Quotation' }}
                            </PrimaryButton>

                            <PrimaryButton v-else-if="totalAmount > 0 && $page.props.auth.user.role === 'regular' && quotation.status === 'sent'"
                                           :disabled="form.processing"
                                           @click="sendQuotation">Send Quotation
                            </PrimaryButton>
                        </div>

                        <div class="flex flex-col">
                            <LastModifiedBy :dateTime="quotation.updated_at"
                                            :user="quotation.last_modified_by" />
                            <p v-if="form.isDirty"
                               class="text-sm text-red-500 italic">*** Any unsaved changes will be discarded.</p>
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
