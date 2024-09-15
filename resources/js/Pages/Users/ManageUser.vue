<script setup>
import {router, useForm} from "@inertiajs/vue3";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import PageHead from "@/Components/PageHead.vue";
import Tag from "@/Components/Tag.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import RoleSelection from "@/Pages/Users/Partials/RoleSelection.vue";
import Card from "@/Components/Card.vue";
import LastModifiedBy from "@/Components/LastModifiedBy.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import TextFilter from "@/Components/TextFilter.vue";
import {useFormatter} from "@/Composables/formatter.js";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    activities: {
        type: Object,
    },
});

const {formatDateTime} = useFormatter();

const roleForm = useForm({
    role: props.user.role,
});

const statusForm = useForm({
    status: '',
});

const roleTagColors = {
    admin: 'text-indigo-500',
    regular: 'text-cyan-500',
    affiliate: 'text-pink-500',
};

const statusTagColors = {
    active: 'text-green-500',
    restricted: 'text-red-500',
};

function updateRole() {
    roleForm.put(route('users.update', {user: props.user.id}));
}

function updateStatus(status) {
    statusForm.status = status;
    statusForm.put(route('users.update', {user: props.user.id}));
}

function deleteAccount() {
    router.delete(route('users.destroy', {user: props.user.id}), {
        onBefore: () => confirm('Attempting to delete this account.'),
    });
}
</script>

<template>
    <BaseLayout>
        <PageHead title="User Profile" />

        <FlashMessage />

        <div class="grid grid-cols-3 gap-6">

            <div class="flex flex-col">
                <Card class="mb-3"
                      title="Details">
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center">
                            <span class="w-16 text-xs text-neutral-600 uppercase font-medium">Name</span>
                            <span>{{ user.name }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-16 text-xs text-neutral-600 uppercase font-medium">Email</span>
                            <span>{{ user.email }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-16 text-xs text-neutral-600 uppercase font-medium">Role</span>
                            <Tag :class="roleTagColors[user.role]">{{ user.role }}</Tag>
                        </div>
                        <div class="flex items-center">
                            <span class="w-16 text-xs text-neutral-600 uppercase font-medium">Status</span>
                            <Tag :class="statusTagColors[user.status]">{{ user.status }}</Tag>
                        </div>

                        <LastModifiedBy :dateTime="user.updated_at"
                                        :user="user.last_modified_by" />
                    </div>
                </Card>

                <Card class="my-3"
                      title="Manage Role">
                    <form @submit.prevent="updateRole">
                        <div class="flex flex-col space-y-3">
                            <RoleSelection v-model="roleForm.role"
                                           @change="roleForm.validate('role')" />
                            <PrimaryButton class="w-max">Update Role</PrimaryButton>
                        </div>
                    </form>
                </Card>

                <Card class="my-3"
                      title="Manage Access">
                    <div v-if="user.status === 'active'"
                         class="flex items-center justify-between space-x-6">
                        <div class="flex flex-col text-sm">
                            <p class="font-medium">Block User</p>
                            <p class="text-neutral-500">Restricting this user will block their access to the system.</p>
                        </div>
                        <DangerButton class="flex-shrink-0"
                                      @click="updateStatus('restricted')">Restrict Access
                        </DangerButton>
                    </div>
                    <div v-else-if="user.status === 'restricted'"
                         class="flex items-center justify-between space-x-6">
                        <div class="flex flex-col text-sm">
                            <p class="font-medium">Unblock User</p>
                            <p class="text-neutral-500">Unblocking this user with restore their access to the system.</p>
                        </div>
                        <SuccessButton class="flex-shrink-0"
                                       @click="updateStatus('active')">Restore Access
                        </SuccessButton>
                    </div>
                </Card>

                <Card class="border-red-300 my-3"
                      title="Danger Zone">
                    <div class="flex items-center justify-between space-x-6">
                        <div class="flex flex-col text-sm">
                            <p class="font-medium">Account Deletion</p>
                            <p class="text-neutral-500">Deleting this account will permanently remove it from the database. This action is irreversible.</p>
                            <p class="text-neutral-500 italic">*** User accounts with linked records can not be deleted.</p>
                        </div>
                        <DangerButton class="flex-shrink-0"
                                      @click="deleteAccount">Delete Account
                        </DangerButton>
                    </div>
                </Card>
            </div>

            <Card title="Activity Log" class="col-span-2">
                <TextFilter />

                <p class="mt-3 text-sm text-neutral-500">Showing up to 15 records of the most recent activities.</p>

                <div class="flex flex-col space-y-3 mt-3">
                    <p v-for="activity in activities" class="flex flex-col">
                        <span>{{ activity.details }}</span>
                        <span class="text-sm text-neutral-500">{{ formatDateTime(activity.created_at) }}</span>
                    </p>
                </div>
            </Card>
        </div>
    </BaseLayout>
</template>
