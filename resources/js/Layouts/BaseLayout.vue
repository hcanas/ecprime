<script setup>
import NavLink from '@/Components/Navigation/NavLink.vue';
import {useCart} from "@/Composables/cart.js";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavDropdown from "@/Components/Navigation/NavDropdown.vue";
import NavDropdownItem from "@/Components/Navigation/NavDropdownItem.vue";
import {ArrowLeftStartOnRectangleIcon, ShoppingCartIcon, UserCircleIcon,} from "@heroicons/vue/16/solid/index.js";
import DarkModeToggle from "@/Components/DarkModeToggle.vue";

const {items} = useCart();
</script>

<template>
    <div class="px-24">
        <nav class="flex items-center space-x-6 py-12">
            <div class="flex items-center space-x-1">
                <ApplicationLogo class="size-10" />
                <div class="flex flex-col">
                    <span class="text-primary-500 dark:text-primary-400 font-bold uppercase leading-tight">EC Prime</span>
                    <span class="text-neutral-700 dark:text-white font-bold uppercase leading-tight">Corporation</span>
                </div>
            </div>

            <div v-if="$page.props.auth.user" class="flex-grow flex items-center">
                <NavLink :active="route().current('dashboard')"
                         :href="route('dashboard')">Dashboard
                </NavLink>
                <NavLink :active="route().current('users.index')"
                         :href="route('users.index')">Users
                </NavLink>
                <NavLink :active="route().current('products.index')"
                         :href="route('products.index')">Products
                </NavLink>
                <NavLink :active="route().current('quotations.index')"
                         :href="route('quotations.index')">Quotations
                </NavLink>
                <NavLink :active="route().current('purchase_orders.index')"
                         :href="route('purchase_orders.index')">Purchase Orders
                </NavLink>
            </div>
            <div v-else class="flex-grow flex items-center">
                <NavLink :active="route().current('home')"
                         :href="route('home')">Home
                </NavLink>
                <NavLink :active="route().current('shop')"
                         :href="route('shop')">Shop
                </NavLink>
                <NavLink :active="route().current('about_us')"
                         :href="route('about_us')">About Us
                </NavLink>
                <NavLink :active="route().current('contact_us.index')"
                         :href="route('contact_us.index')">Contact Us
                </NavLink>
            </div>

            <div class="relative flex items-center space-x-6">
                <NavLink :href="route('cart')"
                         class="text-primary-600 hover:text-primary-500"
                         title="Cart">
                    <div class="relative">
                        <ShoppingCartIcon class="size-5" />
                        <span v-if="items.length"
                              class="absolute -top-3 -right-4 h-6 w-6 text-center text-xs leading-loose text-white bg-primary-500 rounded-full">{{ items.length }}</span>
                    </div>
                </NavLink>

                <DarkModeToggle />

                <NavDropdown v-if="$page.props.auth.user">
                    <template #buttonText>
                        {{ $page.props.auth.user.name }}
                    </template>

                    <template #items>
                        <NavDropdownItem :href="route('profile.edit')">
                            <div class="flex items-center space-x-1 py-1">
                                <UserCircleIcon class="size-4" />
                                <span>Profile</span>
                            </div>
                        </NavDropdownItem>
                        <NavDropdownItem :href="route('logout')"
                                         as="button"
                                         method="post">
                            <div class="flex items-center space-x-1 py-1">
                                <ArrowLeftStartOnRectangleIcon class="size-4" />
                                <span>Logout</span>
                            </div>
                        </NavDropdownItem>
                    </template>
                </NavDropdown>

                <NavLink v-else
                         :href="route('login')">Login
                </NavLink>
            </div>
        </nav>

        <main class="pb-3">
            <slot />
        </main>
    </div>
</template>
