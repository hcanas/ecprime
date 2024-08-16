<script setup>
import {Head, Link} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextFilter from "@/Components/TextFilter.vue";
import CustomTable from "@/Components/CustomTable.vue";
import OnlyFilter from "@/Components/OnlyFilter.vue";
import Pagination from "@/Components/Navigation/Pagination.vue";
import FlashMessage from "@/Components/FlashMessage.vue";

defineProps({
    can: {
        type: Object,
    },
    users: {
        type: Object,
        required: true,
    },
});

const columns = [
    { label: 'Email', field: 'email' },
    { label: 'Name', field: 'name' },
    { label: 'Role', field: 'role' },
    { label: 'Status', field: 'status' },
];

const onlyFilterOptions = [
    { value: 'admin', label: 'Admins Only' },
    { value: 'regular', label: 'Regulars Only' },
    { value: 'affiliate', label: 'Affiliates Only' },
];
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <div class="p-12">
            <Link v-if="can.create_user" :href="route('users.create')">New User</Link>

            <div class="flex items-center">
                <TextFilter />
                <OnlyFilter :options="onlyFilterOptions" field="role" label="Status" />
            </div>

            <FlashMessage />
            <CustomTable :columns="columns" :data="users.data">
                <template #emailCol="{ rowData }">
                    <div class="flex flex-col">
                        <span>{{rowData.email}}</span>
                        <Link v-if="$page.props.auth.user.id !== rowData.id" :href="route('users.edit', { user: rowData.id })">Manage</Link>
                    </div>
                </template>
            </CustomTable>

            <Pagination
                :lastPageUrl="users.last_page_url"
                :firstPageUrl="users.first_page_url"
                :nextPageUrl="users.next_page_url"
                :prevPageUrl="users.prev_page_url"
                :totalRecords="users.total"
                :to="users.to"
                :from="users.from"
                :perPage="users.per_page"
                :currentPage="users.current_page"
            />
        </div>
    </AuthenticatedLayout>
</template>
