<script setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const roleForm = useForm({
    role: props.user.role,
});

const statusForm = useForm({
    status: '',
});

function updateRole() {
    roleForm.put(route('users.update', { user: props.user.id }));
}

function updateStatus(status) {
    statusForm.status = status;
    statusForm.put(route('users.update', { user: props.user.id }));
}

function deleteAccount() {
    router.delete(route('users.destroy', { user: props.user.id }), {
        onBefore: () => confirm('This account will be deleted permanently.'),
    });
}
</script>

<template>
    <Head title="Manage User" />

    <AuthenticatedLayout>
        <div class="flex flex-col space-y-1">
            <div class="grid grid-cols-2">
                <span>Name</span>
                <span>{{user.name}}</span>
            </div>
            <div class="grid grid-cols-2">
                <span>Email</span>
                <span>{{user.email}}</span>
            </div>
            <div class="grid grid-cols-2">
                <span>Role</span>
                <span>{{user.role}}</span>
            </div>
            <div class="grid grid-cols-2">
                <span>Status</span>
                <span>{{user.status}}</span>
            </div>
            <div v-if="user.last_modified_by" class="ml-auto">
                Last Modified By: {{user.last_modified_by.name}}
            </div>
        </div>

        <div class="mt-6">
            <p>Manage Role</p>
            <form @submit.prevent="updateRole">
                <div class="flex flex-col">
                    <div>
                        <label for="admin">Admin</label>
                        <input type="radio" name="role" value="admin" v-model="roleForm.role" />
                    </div>
                    <div>
                        <label for="admin">Regular</label>
                        <input type="radio" name="role" value="regular" v-model="roleForm.role" />
                    </div>
                    <div>
                        <label for="admin">Affiliate</label>
                        <input type="radio" name="role" value="affiliate" v-model="roleForm.role" />
                    </div>
                    <button type="submit">Update Role</button>
                </div>
            </form>
            <p v-if="roleForm.wasSuccessful">Role has been updated.</p>
            <p v-else-if="roleForm.errors.role">{{roleForm.errors.role}}</p>
        </div>

        <div class="mt-6">
            <button v-if="user.status === 'active'" @click="updateStatus('restricted')">Restrict Access</button>
            <button v-else-if="user.status === 'restricted'" @click="updateStatus('active')">Restore Access</button>
            <p v-if="statusForm.wasSuccessful">Status has been updated.</p>
            <p v-else-if="statusForm.errors.status">{{statusForm.errors.status}}</p>
        </div>

        <div class="mt-6">
            <p>Deleting this account will permanently remove all user records. This action is not reversible.</p>
            <button @click="deleteAccount">Delete Account</button>
        </div>
    </AuthenticatedLayout>
</template>
