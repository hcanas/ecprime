<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import {useCart} from "@/Composables/cart.js";
import {onMounted, ref} from "vue";
import {find, forEach, remove} from "lodash";
import CustomTable from "@/Components/CustomTable.vue";
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "laravel-precognition-vue-inertia";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHead from "@/Components/PageHead.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {XMarkIcon} from "@heroicons/vue/16/solid/index.js";
import InputError from "@/Components/InputError.vue";
import Card from "@/Components/Card.vue";

const cart = useCart();

const columns = [
    {label: 'Product', field: 'name'},
    {label: 'Brand', field: 'brand'},
    {label: 'Quantity', field: 'quantity', align: 'right'},
    {label: 'Unit', field: 'measurement_unit'},
];

const items = ref([]);

const form = useForm('post', route('quotations.store'), {
    email: '',
    contact_number: '',
    viber_id: '',
    items: [],
});

function updateItem(id, newData) {
    cart.updateItem(id, newData);

    forEach(items.value, (item, key) => {
        if (item.id === id) {
            items.value[key].quantity = parseInt(newData.quantity ?? item.quantity);
            items.value[key].brand = newData.brand ?? item.brand;
            return false;
        }
    });
}

function removeItem(id) {
    if (confirm('Remove item from cart.')) {
        cart.removeItem(id);
        remove(items.value, x => x.id === id);
    }
}

7

function submit() {
    form.items = cart.items.value;
    form.submit({
        onSuccess: () => {
            form.reset();
            items.value = [];
            cart.clearItems();
        },
    });
}

onMounted(() => {
    axios.get(route('api.cart_items', {ids: cart.getIds()}))
        .then(response => {
            forEach(cart.items.value, item => {
                // remove cart items which were added before product deletion
                if (!find(response.data, x => x.id === item.id)) cart.removeItem(item.id);

                items.value.push({
                    ...find(response.data, x => x.id === item.id),
                    quantity: item.quantity,
                    brand: item.brand,
                })
            });
        });
});
</script>

<template>
    <BaseLayout>
        <PageHead title="Cart" />

        <FlashMessage />

        <form v-if="items.length"
              @submit.prevent="submit">
            <div class="grid grid-cols-4 gap-6">
                <Card class="col-span-3 h-max"
                      title="Items">
                    <CustomTable :columns="columns"
                                 :data="items">
                        <template #nameCol="{ rowData, index }">
                            <div class="flex flex-col space-y-3">
                                <div class="flex items-center space-x-3">
                                    <img :src="rowData.image ? `/storage/images/${rowData.image}` : '/images/placeholder.png'"
                                         alt="Image"
                                         class="size-14" />
                                    <p class="flex flex-col">
                                        <span>{{ rowData.name }}</span>
                                        <span class="text-sm">{{ rowData.description }}</span>
                                    </p>
                                </div>
                                <button class="w-max text-sm text-red-500 hover:underline"
                                        type="button"
                                        @click="removeItem(rowData.id)">
                                    <div class="flex items-center space-x-1">
                                        <XMarkIcon class="size-3" />
                                        <span>Remove</span>
                                    </div>
                                </button>
                                <InputError :message="form.errors[`items.${index}.id`]" />
                            </div>
                        </template>

                        <template #brandCol="{ rowData, index }">
                            <input :value="rowData.brand"
                                   class="px-3 py-2 bg-neutral-100 border-gray-300 rounded-md shadow-sm outline-none focus:ring-0"
                                   @change="updateItem(rowData.id, { brand: $event.target.value })" />
                            <InputError :message="form.errors[`items.${index}.brand`]" />
                        </template>

                        <template #quantityCol="{ rowData, index }">
                            <input :value="rowData.quantity"
                                   class="w-24 text-right px-3 py-2 bg-neutral-100 border-gray-300 rounded-md shadow-sm outline-none focus:ring-0"
                                   min="1"
                                   required
                                   type="number"
                                   @change="updateItem(rowData.id, { quantity: $event.target.value })" />
                            <InputError :message="form.errors[`items.${index}.quantity`]" />
                        </template>
                    </CustomTable>
                </Card>

                <Card class="h-max"
                      title="Contact Information">
                    <div class="flex flex-col space-y-3">
                        <div class="flex flex-col space-y-1">
                            <InputLabel>Email</InputLabel>
                            <TextInput v-model="form.email"
                                       type="email"
                                       @change="form.validate('email')" />
                            <InputError :message="form.errors.email" />
                        </div>
                        <div class="flex flex-col space-y-1">
                            <InputLabel>Contact Number</InputLabel>
                            <TextInput v-model="form.contact_number"
                                       type="number"
                                       @change="form.validate('contact_number')" />
                            <InputError :message="form.errors.contact_number" />
                        </div>
                        <div class="flex flex-col space-y-1">
                            <InputLabel>Viber ID</InputLabel>
                            <TextInput v-model="form.viber_id"
                                       @change="form.validate('viber_id')" />
                            <InputError :message="form.errors.viber_id" />
                        </div>
                        <PrimaryButton>Request Quotation</PrimaryButton>
                    </div>
                </Card>
            </div>
        </form>
        <span v-else
              class="text-red-600">Your cart is empty.</span>
    </BaseLayout>
</template>
