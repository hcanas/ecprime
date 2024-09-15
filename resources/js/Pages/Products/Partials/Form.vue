<script setup>
import {useForm} from "laravel-precognition-vue-inertia";
import SuggestionInput from "@/Components/SuggestionInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Card from "@/Components/Card.vue";
import {PhotoIcon} from "@heroicons/vue/16/solid/index.js";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import {nextTick, ref, watch} from "vue";
import LastModifiedBy from "@/Components/LastModifiedBy.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    product: {
        type: Object,
    },
});

const form = useForm('post',
    props.product ? route('products.update', {product: props.product.id}) : route('products.store'),
    {
        image: '',
        name: props.product?.name ?? '',
        description: props.product?.description ?? '',
        price: props.product?.price ?? '',
        measurement_unit: props.product?.measurement_unit ?? '',
        main_category: props.product?.main_category ?? '',
        sub_category: props.product?.sub_category ?? '',
        status: props.product?.status ?? 'available',
    }
);

const statusOptions = [
    {value: 'available', label: 'Available'},
    {value: 'unavailable', label: 'Unavailable'},
    {value: 'archived', label: 'Archived'},
];

const textarea = ref(null);
const imagePreview = ref(props.product?.image ? '/storage/images/' + props.product.image : '/images/placeholder.png');

function uploadFile(event) {
    form.image = event.target.files[0];
    imagePreview.value = URL.createObjectURL(event.target.files[0]);
}

function discardChanges() {
    imagePreview.value = props.product?.image ? '/storage/images/' + props.product.image : '/images/placeholder.png';
    form.reset();
}

function submit() {
    if (!form.isDirty) {
        alert('There are no changes.');
        return;
    }

    if (props.product) {
        form.transform(data => ({
            ...data,
            _method: 'put',
        }));
    }

    form.submit();
}

watch(() => form.description, () => {
    nextTick(() => {
        textarea.value.input.style.height = textarea.value.input.scrollHeight + 'px';
    });
});
</script>

<template>
    <form @submit.prevent>
        <div class="flex flex-col space-y-6">
            <Card :title="'Product Image'">
                <div class="flex flex-col items-center space-y-3">
                    <img :src="imagePreview"
                         class="w-[200px] h-[200px]" />
                    <span class="text-sm italic text-neutral-500">Images will be resized to 640x640.</span>
                    <InputLabel for="file">
                        <div class="flex items-center space-x-1 px-3 py-2 hover:text-white bg-neutral-100 hover:bg-primary-500 hover:cursor-pointer border rounded-md transition ease-in-out">
                            <PhotoIcon class="size-5" />
                            <span>{{ product?.image ? 'Replace Photo' : 'Upload Photo' }}</span>
                        </div>
                        <input id="file"
                               accept="image/png, image/jpg, image/jpeg"
                               class="hidden"
                               type="file"
                               @input="uploadFile($event)" />
                    </InputLabel>
                    <InputError :message="form.errors.image" />
                </div>
            </Card>

            <Card :title="'General Information'"
                  class="h-max">
                <div class="flex flex-col space-y-3">
                    <div class="flex flex-col">
                        <InputLabel>Name</InputLabel>
                        <TextInput v-model="form.name"
                                   @change="form.validate('name')" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="flex flex-col">
                        <InputLabel>Description</InputLabel>
                        <TextAreaInput ref="textarea"
                                       v-model="form.description"
                                       class="min-h-44 overflow-hidden resize-none"
                                       @change="form.validate('description')" />
                        <InputError :message="form.errors.description" />
                    </div>
                </div>
            </Card>

            <Card :title="'Pricing'">
                <div class="flex flex-col space-y-3">
                    <div class="flex flex-col">
                        <InputLabel>Unit Price</InputLabel>
                        <TextInput v-model="form.price"
                                   type="number"
                                   @change="form.validate('price')" />
                        <InputError :message="form.errors.price" />
                    </div>

                    <div class="flex flex-col">
                        <InputLabel>Measurement Unit</InputLabel>
                        <SuggestionInput v-model="form.measurement_unit"
                                         :src="route('api.measurement_units')"
                                         @change="form.validate('measurement_unit')" />
                        <InputError :message="form.errors.description" />
                    </div>
                </div>
            </Card>

            <Card :title="'Category'">
                <div class="flex flex-col space-y-3">
                    <div class="flex flex-col">
                        <InputLabel>Main Category</InputLabel>
                        <SuggestionInput v-model="form.main_category"
                                         :src="route('api.main_categories')"
                                         @change="form.validate('main_category')" />
                        <InputError :message="form.errors.main_category" />
                    </div>

                    <div class="flex flex-col">
                        <InputLabel>Sub Category</InputLabel>
                        <SuggestionInput :key="form.main_category"
                                         v-model="form.sub_category"
                                         :src="route('api.sub_categories', { main_category: form.main_category })"
                                         @change="form.validate('sub_category')" />
                        <InputError :message="form.errors.sub_category" />
                    </div>
                </div>
            </Card>

            <Card :title="'Other Information'">
                <div class="flex flex-col">
                    <InputLabel>Status</InputLabel>
                    <SelectInput v-model="form.status"
                                 :options="statusOptions" />
                    <InputError :message="form.errors.status" />
                </div>
            </Card>

            <div class="flex flex-row-reverse items-start justify-between">
                <div class="flex items-center space-x-1">
                    <SecondaryButton v-if="product && form.isDirty"
                                     @click="discardChanges">Discard Changes
                    </SecondaryButton>
                    <PrimaryButton :disabled="form.processing"
                                   @click="submit">{{ product?.id ? 'Save Changes' : 'Create Product' }}
                    </PrimaryButton>
                </div>

                <div class="flex flex-col">
                    <LastModifiedBy :dateTime="product?.updated_at"
                                    :user="product?.last_modified_by" />
                    <p v-if="product && form.isDirty"
                       class="text-sm text-red-500 italic">*** Any unsaved changes will be discarded.</p>
                </div>
            </div>
        </div>
    </form>
</template>
