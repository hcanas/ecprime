<script setup>
import {Link} from "@inertiajs/vue3";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import PageHead from "@/Components/PageHead.vue";
import TextFilter from "@/Components/TextFilter.vue";
import CategoryFilter from "@/Components/CategoryFilter.vue";
import {onMounted, ref} from "vue";
import {forEach} from "lodash";
import Pagination from "@/Components/Navigation/Pagination.vue";
import {useCart} from "@/Composables/cart.js";
import {ShoppingCartIcon} from "@heroicons/vue/16/solid/index.js";

defineProps({
    products: {
        type: Object,
        required: true,
    },
});

const {itemExists} = useCart();

const categories = ref([]);

onMounted(() => {
    axios.get(route('api.categories'))
        .then(response => {
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
                <TextFilter />
                <CategoryFilter v-if="categories.length"
                                :options="categories" />
            </div>
        </PageHead>

        <div class="grid grid-cols-5 gap-6">
            <Link v-for="product in products.data"
                  :href="route('product.profile', { product: product.id })"
                  class="relative flex flex-col items-center hover:text-primary-500 p-3 border hover:border-primary-500 dark:text-white dark:hover:text-primary-500 dark:bg-neutral-900 dark:border-neutral-800 rounded-md shadow transition ease-in-out">
                <img :src="product.image ? `/storage/images/${product.image}` : '/images/placeholder.png'"
                     class="w-[200px] h-[200px]" />
                <span class="text-center">{{ product.name }}</span>
                <ShoppingCartIcon v-if="itemExists(product.id)" class="size-5 text-primary-500 absolute top-2 left-2" />
            </Link>
        </div>

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
    </BaseLayout>
</template>
