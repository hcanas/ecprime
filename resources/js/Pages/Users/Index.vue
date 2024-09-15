<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import TextFilter from "@/Components/TextFilter.vue";
import CustomTable from "@/Components/CustomTable.vue";
import OnlyFilter from "@/Components/OnlyFilter.vue";
import Pagination from "@/Components/Navigation/Pagination.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHead from "@/Components/PageHead.vue";
import ButtonLink from "@/Components/Navigation/ButtonLink.vue";
import {PencilIcon, UserPlusIcon} from "@heroicons/vue/16/solid/index.js";
import {Link} from "@inertiajs/vue3";
import Tag from "@/Components/Tag.vue";

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
    {label: 'Email', field: 'email'},
    {label: 'Name', field: 'name'},
    {label: 'Role', field: 'role'},
    {label: 'Status', field: 'status'},
];

const onlyFilterOptions = [
    {value: 'admin', label: 'Admins'},
    {value: 'regular', label: 'Regulars'},
    {value: 'affiliate', label: 'Affiliates'},
];

const roleTagColors = {
    admin: 'text-indigo-500',
    regular: 'text-cyan-500',
    affiliate: 'text-pink-500',
};

const statusTagColors = {
    active: 'text-green-500',
    restricted: 'text-red-500',
};
</script>

<template>
    <BaseLayout>
        <PageHead title="Users">
            <div class="flex items-center space-x-1">
                <ButtonLink v-if="can.create_user"
                            :href="route('users.create')">
                    <div class="flex items-center space-x-1">
                        <UserPlusIcon class="size-4" />
                        <span>New User</span>
                    </div>
                </ButtonLink>
                <TextFilter />
                <OnlyFilter :options="onlyFilterOptions"
                            field="role"
                            label="Role" />
            </div>
        </PageHead>

        <FlashMessage />
        <CustomTable :columns="columns"
                     :data="users.data">
            <template #emailCol="{ rowData }">
                <div class="group flex items-center space-x-3">
                    <span>{{ rowData.email }}</span>
                    <Link v-if="rowData.id !== $page.props.auth.user.id"
                          :href="route('users.edit', { user: rowData.id })"
                          class="text-sm text-blue-500 hover:underline">
                        <div class="flex items-center">
                            <PencilIcon class="size-3" />
                            <span>Manage</span>
                        </div>
                    </Link>
                </div>
            </template>

            <template #roleCol="{ rowData }">
                <Tag :class="roleTagColors[rowData.role]">
                    {{ rowData.role }}
                </Tag>
            </template>

            <template #statusCol="{ rowData }">
                <Tag :class="statusTagColors[rowData.status]">
                    {{ rowData.status }}
                </Tag>
            </template>
        </CustomTable>

        <Pagination
            :currentPage="users.current_page"
            :firstPageUrl="users.first_page_url"
            :from="users.from"
            :lastPageUrl="users.last_page_url"
            :nextPageUrl="users.next_page_url"
            :perPage="users.per_page"
            :prevPageUrl="users.prev_page_url"
            :to="users.to"
            :totalRecords="users.total"
        />
    </BaseLayout>
</template>
