<script setup>
import {Head} from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import {nextTick, ref, watch} from "vue";
import {useForm} from "laravel-precognition-vue-inertia";
import FlashMessage from "@/Components/FlashMessage.vue";

const form = useForm('post', route('contact_us.store'), {
    name: '',
    email: '',
    contact_number: '',
    message: '',
});

const textarea = ref(null);

function submit() {
    form.submit({
        onSuccess: () => form.reset(),
    });
}

watch(() => form.message, () => {
    nextTick(() => {
        textarea.value.input.style.height = textarea.value.input.scrollHeight + 'px';
    });
});
</script>

<template>
    <BaseLayout>
        <section class="w-full md:max-w-7xl md:mx-auto px-3 md:px-0 py-6">
            <Head title="Contact Us" />

            <div class="flex flex-col items-center">
                <div class="flex flex-col space-y-3">
                    <h1 class="uppercase text-center font-medium leading-4">We are here to help!</h1>
                    <h5 class="text-center font-medium italic">For questions or concerns about our services, send us a message and we will respond within 24 hours.</h5>
                </div>

                <div class="w-full flex flex-col md:flex-row md:space-x-12 mt-6 md:mt-12">
                    <div class="flex-shrink-0 hidden md:block">
                        <img class="w-[40rem] rounded"
                             src="/images/contactUs2.jpg" />
                    </div>
                    <form class="flex-grow"
                          @submit.prevent="submit">
                        <div class="w-full flex flex-col space-y-3 md:px-6 md:px-0">
                            <FlashMessage />
                            <div class="flex flex-col">
                                <InputLabel value="Name" />
                                <TextInput v-model="form.name"
                                           @change="form.validate('name')" />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="flex flex-col">
                                <InputLabel value="Email Address" />
                                <TextInput v-model="form.email"
                                           @change="form.validate('email')" />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div class="flex flex-col">
                                <InputLabel value="Contact Number" />
                                <TextInput v-model="form.contact_number"
                                           type="number"
                                           @change="form.validate('contact_number')" />
                                <InputError :message="form.errors.contact_number" />
                            </div>
                            <div class="flex flex-col">
                                <InputLabel value="Message" />
                                <TextAreaInput ref="textarea"
                                               v-model="form.message"
                                               class="min-h-[10rem] resize-none overflow-hidden"
                                               @change="form.validate('message')" />
                                <InputError :message="form.errors.message" />
                            </div>

                            <PrimaryButton>Send</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </BaseLayout>
</template>
