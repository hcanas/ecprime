<script setup>
import {useForm} from "laravel-precognition-vue-inertia";
import SuggestionInput from "@/Components/SuggestionInput.vue";
import FlashMessage from "@/Components/FlashMessage.vue";

const props = defineProps({
    product: {
        type: Object,
    },
});

const form = useForm(
    props.product ? 'put' : 'post',
    props.product ? route( 'products.update', { product: props.product.id }) : route('products.store'),
    {
        name: props.product?.name ?? '',
        description: props.product?.description ?? '',
        price: props.product?.price ?? '',
        measurement_unit: props.product?.measurement_unit ?? '',
        main_category: props.product?.main_category ?? '',
        sub_category: props.product?.sub_category ?? '',
        status: props.product?.status ?? 'available',
    }
);

function submit() {
    form.submit();
}
</script>

<template>
    <FlashMessage />
    <form @submit.prevent="submit">
        <div class="flex flex-col">
            <div class="flex flex-col">
                <label>Name</label>
                <input type="text" v-model="form.name" @change="form.validate('name')" />
                <span v-if="form.invalid('name')">{{form.errors.name}}</span>
            </div>

            <div class="flex flex-col">
                <label>Description</label>
                <input type="text" v-model="form.description" @change="form.validate('description')" />
                <span v-if="form.invalid('description')">{{form.errors.description}}</span>
            </div>

            <div class="flex flex-col">
                <label>Price</label>
                <input type="number" v-model="form.price" @change="form.validate('price')" />
                <span v-if="form.invalid('price')">{{form.errors.price}}</span>
            </div>

            <div class="flex flex-col">
                <label>Measurement Unit</label>
                <SuggestionInput :src="route('api.measurement_units')" v-model="form.measurement_unit" @change="form.validate('measurement_unit')" />
                <span v-if="form.invalid('measurement_unit')">{{form.errors.measurement_unit}}</span>
            </div>

            <div class="flex flex-col">
                <label>Main Category</label>
                <SuggestionInput :src="route('api.main_categories')" v-model="form.main_category" @change="form.validate('main_category')" />
                <span v-if="form.invalid('main_category')">{{form.errors.main_category}}</span>
            </div>

            <div class="flex flex-col">
                <label>Sub Category</label>
                <SuggestionInput :key="form.main_category" :src="route('api.sub_categories', { main_category: form.main_category })" v-model="form.sub_category" @change="form.validate('sub_category')" />
                <span v-if="form.invalid('sub_category')">{{form.errors.sub_category}}</span>
            </div>

            <div class="flex flex-col">
                <label>Status</label>
                <div class="flex flex-col">
                    <div>
                        <label for="admin">Available</label>
                        <input type="radio" name="status" value="available" v-model="form.status" />
                    </div>
                    <div>
                        <label for="admin">Unavailable</label>
                        <input type="radio" name="status" value="unavailable" v-model="form.status" />
                    </div>
                    <div>
                        <label for="admin">Archived</label>
                        <input type="radio" name="status" value="archived" v-model="form.status" />
                    </div>
                </div>
                <p v-if="form.invalid('status')">{{form.errors.status}}</p>
            </div>

            <button type="submit">Save</button>
        </div>
    </form>
</template>
