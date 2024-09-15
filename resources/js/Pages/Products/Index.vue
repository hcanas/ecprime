<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import TextFilter from "@/Components/TextFilter.vue";
import CustomTable from "@/Components/CustomTable.vue";
import Pagination from "@/Components/Navigation/Pagination.vue";
import OnlyFilter from "@/Components/OnlyFilter.vue";
import {onMounted, ref} from "vue";
import CategoryFilter from "@/Components/CategoryFilter.vue";
import {forEach} from "lodash";
import FlashMessage from "@/Components/FlashMessage.vue";
import {useCart} from "@/Composables/cart.js";
import PageHead from "@/Components/PageHead.vue";
import ButtonLink from "@/Components/Navigation/ButtonLink.vue";
import {PencilIcon, PlusIcon, XMarkIcon} from "@heroicons/vue/16/solid/index.js";
import {useFormatter} from "@/Composables/formatter.js";
import Tag from "@/Components/Tag.vue";

defineProps({
    can: {
        type: Object,
    },
    products: {
        type: Object,
        required: true,
    },
});

const cart = useCart();
const {formatCurrency} = useFormatter();

const columns = [
    {label: 'Image', field: 'image'},
    {label: 'Name', field: 'name'},
    {label: 'Unit', field: 'measurement_unit'},
    {label: 'Category', field: 'category'},
    {label: 'Status', field: 'status'},
];

if (usePage().props.auth.user.role !== 'affiliate') {
    columns.push({label: 'Price', field: 'price', align: 'right'});
}

const categories = ref([]);

const onlyFilterOptions = [
    {value: 'available', label: 'Available'},
    {value: 'unavailable', label: 'Unavailable'},
    {value: 'archived', label: 'Archived'},
];

const statusTagColors = {
    available: 'text-green-500',
    unavailable: 'text-red-500',
    archived: 'text-indigo-500',
};

onMounted(() => {
    axios.get(route('api.categories'))
        .then(response => {

            console.log(response.data);
            forEach(response.data, category => {
                categories.value.push({name: category.name, level: 'main'});

                forEach(category.sub_categories, subCategory => {
                    categories.value.push({name: subCategory.name, parent: category.name, level: 'sub'});
                });
            });
        });
});
</script>

<template>
    <BaseLayout>
        <PageHead title="Products">
            <div class="flex items-center space-x-1">
                <ButtonLink v-if="can.create_product"
                            :href="route('products.create')">
                    <div class="flex items-center space-x-1">
                        <PlusIcon class="size-4" />
                        <span>New Product</span>
                    </div>
                </ButtonLink>
                <TextFilter />
                <CategoryFilter v-if="categories.length"
                                :options="categories" />
                <OnlyFilter v-if="$page.props.auth.user.role !== 'affiliate'"
                            :options="onlyFilterOptions"
                            field="status"
                            label="Status" />
            </div>
        </PageHead>

        <div class="flex flex-col">
            <FlashMessage />

            <CustomTable :columns="columns"
                         :data="products.data">
                <template #imageCol="{ rowData }">
                    <img :src="rowData.image ? `/storage/images/${rowData.image}` : '/images/placeholder.png'"
                         alt="Image"
                         class="size-16" />
                </template>

                <template #nameCol="{ rowData }">
                    <div class="flex flex-col">
                        <span>{{ rowData.name }}</span>
                        <span class="text-sm max-w-96 truncate">{{ rowData.description }}</span>

                        <div class="flex items-center space-x-3">
                            <Link v-if="can.edit_product"
                                  :href="route('products.edit', { product: rowData.id })"
                                  class="text-sm text-blue-500 hover:underline">
                                <div class="flex items-center space-x-1">
                                    <PencilIcon class="size-3" />
                                    <span>Edit</span>
                                </div>
                            </Link>
                            <button v-if="rowData.status === 'available' && !cart.itemExists(rowData.id)"
                                    class="text-sm text-blue-500 hover:underline"
                                    @click="cart.addItem(rowData.id)">
                                <div class="flex items-center space-x-1">
                                    <PlusIcon class="size-3" />
                                    <span>Add To Cart</span>
                                </div>
                            </button>
                            <button v-else-if="cart.itemExists(rowData.id)"
                                    class="text-sm text-red-500 hover:underline"
                                    @click="cart.removeItem(rowData.id)">
                                <div class="flex items-center space-x-1">
                                    <XMarkIcon class="size-3" />
                                    <span>Remove From Cart</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </template>

                <template #priceCol="{ rowData }">
                    {{ formatCurrency(rowData.price) }}
                </template>

                <template #categoryCol="{ rowData }">
                    {{ rowData.sub_category ? `${rowData.sub_category} (${rowData.main_category})` : rowData.main_category }}
                </template>

                <template #statusCol="{ rowData }">
                    <Tag :class="statusTagColors[rowData.status]">{{ rowData.status }}</Tag>
                </template>
            </CustomTable>

            <Pagination
                :currentPage="products.current_page"
                :firstPageUrl="products.first_page_url"
                :from="products.from"
                :lastPageUrl="products.last_page_url"
                :nextPageUrl="products.next_page_url"
                :perPage="products.per_page"
                :prevPageUrl="products.prev_page_url"
                :to="products.to"
                :totalRecords="products.total"
            />
        </div>
    </BaseLayout>
</template>
