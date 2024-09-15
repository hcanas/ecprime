<script setup>
import {useForm} from "laravel-precognition-vue-inertia";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PageHead from "@/Components/PageHead.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RoleSelection from "@/Pages/Users/Partials/RoleSelection.vue";
import Card from "@/Components/Card.vue";

defineProps({
    user: {
        type: Object,
    },
});

const form = useForm('post', route('users.store'), {
    name: '',
    email: '',
    role: 'regular',
});

function submit() {
    form.submit();
}
</script>

<template>
    <BaseLayout>
        <PageHead class="max-w-3xl mx-auto"
                  title="User Registration" />

        <form @submit.prevent="submit">
            <div class="max-w-3xl mx-auto flex flex-col space-y-3">
                <Card title="General Information">
                    <div class="flex flex-col space-y-3">
                        <div class="flex flex-col">
                            <InputLabel>Name</InputLabel>
                            <TextInput v-model="form.name"
                                       @change="form.validate('name')" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="flex flex-col">
                            <InputLabel>Email</InputLabel>
                            <TextInput v-model="form.email"
                                       type="text"
                                       @change="form.validate('email')" />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="flex flex-col">
                            <InputLabel>Role</InputLabel>
                            <RoleSelection v-model="form.role" />
                            <InputError :message="form.errors.role" />
                        </div>
                    </div>

                    <PrimaryButton class="mt-3 mr-0">Create Account</PrimaryButton>
                </Card>
            </div>
        </form>
    </BaseLayout>
</template>
