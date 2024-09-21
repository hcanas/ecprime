<script setup>
import BaseLayout from '@/Layouts/BaseLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import PageHead from "@/Components/PageHead.vue";
import {usePage} from "@inertiajs/vue3";
import Tag from "@/Components/Tag.vue";
import Card from "@/Components/Card.vue";
import TextFilter from "@/Components/TextFilter.vue";
import {useFormatter} from "@/Composables/formatter.js";

defineProps({
    status: {
        type: String,
    },
    activities: {
        type: Object,
    },
});

const {formatDateTime} = useFormatter();

const user = usePage().props.auth.user;

const roleTagColors = {
    admin: 'text-indigo-500',
    regular: 'text-cyan-500',
    affiliate: 'text-pink-500',
};
</script>

<template>
    <BaseLayout>
        <PageHead title="My Profile" />

        <div class="flex flex-col xl:grid xl:grid-cols-3 xl:gap-x-6 gap-y-3">
            <div class="flex flex-col gap-y-6">
                <Card title="General Information">
                    <div class="flex flex-col gap-y-3">
                        <p class="flex items-center">
                            <span class="w-16 text-xs text-neutral-500 uppercase font-medium">Name</span>
                            <span>{{ user.name }}</span>
                        </p>
                        <p class="flex items-center">
                            <span class="w-16 text-xs text-neutral-500 uppercase font-medium">Email</span>
                            <span>{{ user.email }}</span>
                        </p>
                        <p class="flex items-center">
                            <span class="w-16 text-xs text-neutral-500 uppercase font-medium">Role</span>
                            <Tag :class="roleTagColors[user.role]">{{ user.role }}</Tag>
                        </p>
                    </div>
                </Card>

                <UpdatePasswordForm />
            </div>

            <Card v-if="activities.length" title="Activity Log" class="xl:col-span-2">
                <TextFilter />

                <div class="flex flex-col gap-y-3 mt-3">
                    <p v-for="activity in activities" class="flex flex-col">
                        <span>{{ activity.details }}</span>
                        <span class="text-sm text-neutral-500">{{ formatDateTime(activity.created_at) }}</span>
                    </p>
                </div>
            </Card>
        </div>

    </BaseLayout>
</template>
