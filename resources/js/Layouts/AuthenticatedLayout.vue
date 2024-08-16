<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/Navigation/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="w-screen min-h-screen">
        <div class="flex items-stretch">
            <nav class="flex-shrink-0 w-72 min-h-screen bg-neutral-100 flex flex-col space-y-12 p-12">
                <div class="flex-shrink-0">
                    EC Prime
                </div>

                <div class="flex flex-col space-y-1">
                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                    <NavLink :href="route('users.index')" :active="route().current('users.index')">Users</NavLink>
                    <NavLink :href="route('products.index')" :active="route().current('products.index')">Products</NavLink>
                </div>

                <div class="flex-shrink-0">
                    <NavLink :href="route('profile.edit')" :active="route().current('profile.edit')">My Profile</NavLink>
                    <NavLink :href="route('logout')" method="post" as="button">Logout</NavLink>
                </div>
            </nav>

            <main class="flex-grow overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>
