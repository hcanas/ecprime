<script setup>
import {Head} from "@inertiajs/vue3";
import {useForm} from "laravel-precognition-vue-inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    user: {
        type: Object,
    },
});

const form = useForm('post', route('users.store'), {
    name: '',
    email: '',
    role: '',
});

function submit() {
    form.submit();
}

</script>

<template>
    <Head title="Register User" />

    <AuthenticatedLayout>
        <form @submit.prevent="submit">
            <div class="flex flex-col space-y-3">
                <div>
                    <label>Name</label>
                    <input type="text" v-model="form.name" @change="form.validate('name')" />
                    <p v-if="form.invalid('name')">{{form.errors.name}}</p>
                </div>

                <div>
                    <label>Email</label>
                    <input type="text" v-model="form.email" @change="form.validate('email')" />
                    <p v-if="form.invalid('email')">{{form.errors.email}}</p>
                </div>

                <div>
                    <label>Role</label>
                    <div class="flex flex-col">
                        <div>
                            <label for="admin">Admin</label>
                            <input type="radio" name="role" value="admin" v-model="form.role" />
                        </div>
                        <div>
                            <label for="admin">Regular</label>
                            <input type="radio" name="role" value="regular" v-model="form.role" />
                        </div>
                        <div>
                            <label for="admin">Affiliate</label>
                            <input type="radio" name="role" value="affiliate" v-model="form.role" />
                        </div>
                    </div>
                    <p v-if="form.invalid('role')">{{form.errors.role}}</p>
                </div>

                <p v-if="form.wasSuccessful">An email has been sent to the registered user.</p>

                <button type="submit">Submit</button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
