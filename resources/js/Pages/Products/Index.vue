<script setup>
import {Head, Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextFilter from "@/Components/TextFilter.vue";
import CustomTable from "@/Components/CustomTable.vue";
import Pagination from "@/Components/Navigation/Pagination.vue";
import OnlyFilter from "@/Components/OnlyFilter.vue";
import {onMounted, ref} from "vue";
import CategoryFilter from "@/Components/CategoryFilter.vue";
import {forEach} from "lodash";
import FlashMessage from "@/Components/FlashMessage.vue";

defineProps({
    can: {
        type: Object,
    },
    products: {
        type: Object,
        required: true,
    },
});

const columns = [
    { label: 'Name', field: 'name' },
    { label: 'Description', field: 'description' },
    { label: 'Measurement Unit', field: 'measurement_unit' },
    { label: 'Price', field: 'price' },
    { label: 'Category', field: 'category' },
    { label: 'Status', field: 'status' },
];

const onlyFilterOptions = [
    { value: 'available', label: 'Available Only' },
    { value: 'unavailable', label: 'Unavailable Only' },
    { value: 'archived', label: 'Archived Only' },
];

const categories = ref([]);

onMounted(() => {
    axios.get(route('api.categories'))
        .then(response => {
            forEach(response.data, category => {
                categories.value.push({ name: category.name, level: 'main' });

                forEach(category.sub_categories, subCategory => {
                    categories.value.push({ name: subCategory.name, parent: category.name, level: 'sub' });
                });
            });
        });
});
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <div class="flex flex-col">
            <Link v-if="can.create_product" :href="route('products.create')">New Product</Link>

            <div class="flex items-center">
                <TextFilter />
                <CategoryFilter v-if="categories.length" :options="categories" />
                <OnlyFilter :options="onlyFilterOptions" field="status" label="Products" />
            </div>

            <FlashMessage />
            <CustomTable :columns="columns" :data="products.data">
                <template #nameCol="{ rowData }">
                    <div class="flex flex-col">
                        <span>{{rowData.name}}</span>
                        <Link :href="route('products.edit', { product: rowData.id })">Manage</Link>
                    </div>
                </template>
            </CustomTable>

            <Pagination
                :lastPageUrl="products.last_page_url"
                :firstPageUrl="products.first_page_url"
                :nextPageUrl="products.next_page_url"
                :prevPageUrl="products.prev_page_url"
                :totalRecords="products.total"
                :to="products.to"
                :from="products.from"
                :perPage="products.per_page"
                :currentPage="products.current_page"
            />
        </div>
    </AuthenticatedLayout>
</template>
