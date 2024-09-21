<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import NavLink from '@/Components/Navigation/NavLink.vue';
import {useCart} from "@/Composables/cart.js";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {
    Bars3Icon,
    XMarkIcon,
    HomeIcon,
    BuildingStorefrontIcon,
    QuestionMarkCircleIcon,
    PhoneArrowDownLeftIcon,
    ShoppingCartIcon,
    UserIcon,
    UsersIcon,
    UserCircleIcon,
    ArrowLeftStartOnRectangleIcon,
    PresentationChartLineIcon,
    Square3Stack3DIcon,
    TruckIcon,
} from "@heroicons/vue/16/solid/index.js";
import {ref} from "vue";
import DarkModeToggle from "@/Components/DarkModeToggle.vue";
import UserMenu from "@/Components/Navigation/UserMenu.vue";
import UserMenuItem from "@/Components/Navigation/UserMenuItem.vue";

const {items} = useCart();

const user = usePage().props.auth.user;

const mobileMenuOpened = ref(false);
</script>

<template>
    <div class="w-full h-full flex flex-col overflow-hidden xl:overflow-y-auto">
        <nav class="flex-shrink-0 p-3 z-[100] shadow xl:backdrop-blur-sm xl:bg-white/85 dark:xl:bg-neutral-950/95 md:px-6 xl:sticky xl:top-0 xl:inset-x-0 xl:px-24 xl:shadow-none">
            <div class="w-full flex items-center justify-between gap-x-3">
                <Bars3Icon v-if="!mobileMenuOpened"
                           class="flex-shrink-0 text-primary-500 size-5 xl:hidden"
                           @click="mobileMenuOpened = !mobileMenuOpened" />
                <XMarkIcon v-else
                           class="flex-shrink-0 text-primary-500 size-5 xl:hidden"
                           @click="mobileMenuOpened = !mobileMenuOpened" />

                <div :class="mobileMenuOpened ? 'translate-x-0' : '-translate-x-full xl:translate-x-0'"
                     class="absolute top-14 -left-3 z-[100] w-[calc(100vw+0.75rem)] h-[calc(100vh-3.5rem)] backdrop-blur-sm bg-white/90 dark:bg-neutral-950/90 px-3 md:px-6 xl:relative xl:top-0 xl:left-0 xl:w-auto xl:h-auto xl:order-2 xl:backdrop-blur-none xl:bg-transparent transition transform">
                    <div v-if="user"
                         class="flex flex-col xl:flex-row">
                        <NavLink v-if="user.role !== 'affiliate'"
                                 :active="route().current('dashboard')"
                                 :href="route('dashboard')">
                            <div class="flex items-center gap-x-3">
                                <PresentationChartLineIcon class="size-5 xl:hidden" />
                                <span>Dashboard</span>
                            </div>
                        </NavLink>
                        <NavLink v-if="user.role !== 'affiliate'"
                                 :active="route().current('users.index')"
                                 :href="route('users.index')">
                            <div class="flex items-center gap-x-3">
                                <UsersIcon class="size-5 xl:hidden" />
                                <span>Users</span>
                            </div>
                        </NavLink>
                        <NavLink :active="route().current('products.index')"
                                 :href="route('products.index')">
                            <div class="flex items-center gap-x-3">
                                <BuildingStorefrontIcon class="size-5 xl:hidden" />
                                <span>Products</span>
                            </div>
                        </NavLink>
                        <NavLink :active="route().current('quotations.index')"
                                 :href="route('quotations.index')">
                            <div class="flex items-center gap-x-3">
                                <Square3Stack3DIcon class="size-5 xl:hidden" />
                                <span>Quotations</span>
                            </div>
                        </NavLink>
                        <NavLink :active="route().current('purchase_orders.index')"
                                 :href="route('purchase_orders.index')">
                            <div class="flex items-center gap-x-3">
                                <TruckIcon class="size-5 xl:hidden" />
                                <span>Purchase Orders</span>
                            </div>
                        </NavLink>
                    </div>
                    <div v-else
                         class="flex flex-col xl:flex-row">
                        <NavLink :active="route().current('home')"
                                 :href="route('home')">
                            <div class="flex items-center gap-x-3">
                                <HomeIcon class="size-5 xl:hidden" />
                                <span>Home</span>
                            </div>
                        </NavLink>
                        <NavLink :active="route().current('shop')"
                                 :href="route('shop')">
                            <div class="flex items-center gap-x-3">
                                <BuildingStorefrontIcon class="size-5 xl:hidden" />
                                <span>Shop</span>
                            </div>
                        </NavLink>
                        <NavLink :active="route().current('about_us')"
                                 :href="route('about_us')">
                            <div class="flex items-center gap-x-3">
                                <QuestionMarkCircleIcon class="size-5 xl:hidden" />
                                <span>About Us</span>
                            </div>
                        </NavLink>
                        <NavLink :active="route().current('contact_us.index')"
                                 :href="route('contact_us.index')">
                            <div class="flex items-center gap-x-3">
                                <PhoneArrowDownLeftIcon class="size-5 xl:hidden" />
                                <span>Contact Us</span>
                            </div>
                        </NavLink>
                    </div>
                </div>

                <Link :href="route('home')"
                      class="flex-shrink-0 flex items-center gap-x-1 xl:order-1">
                    <ApplicationLogo class="size-6" />
                    <div class="flex flex-col md:flex-row md:items-center md:gap-x-0.5">
                        <span class="text-xs md:text-base text-primary-500 dark:text-primary-400 font-bold uppercase leading-tight">EC Prime</span>
                        <span class="text-xs md:text-base text-neutral-700 dark:text-white font-bold uppercase leading-tight">Corporation</span>
                    </div>
                </Link>

                <div class="flex-grow flex items-center justify-end gap-x-3 xl:order-3">
                    <NavLink :href="route('cart')"
                             :class="{ 'me-3 xl:me-0': items.length }"
                             class="text-primary-600 hover:text-primary-500"
                             title="Cart">
                        <div class="relative">
                            <ShoppingCartIcon class="dark:text-primary-400 size-5" />
                            <span v-if="items.length"
                                  class="absolute -top-3 -right-4 h-6 w-6 text-center text-xs leading-loose text-white bg-primary-700 dark:bg-neutral-700 rounded-full">{{ items.length }}</span>
                        </div>
                    </NavLink>

                    <DarkModeToggle />

                    <UserMenu v-if="$page.props.auth.user">
                        <template #items>
                            <UserMenuItem :href="route('profile.edit')">
                                <div class="flex items-center justify-center gap-x-1 py-2">
                                    <UserCircleIcon class="size-4" />
                                    <span>Profile</span>
                                </div>
                            </UserMenuItem>
                            <UserMenuItem :href="route('logout')"
                                             as="button"
                                             method="post">
                                <div class="flex items-center justify-center gap-x-1 py-2">
                                    <ArrowLeftStartOnRectangleIcon class="size-4" />
                                    <span>Logout</span>
                                </div>
                            </UserMenuItem>
                        </template>
                    </UserMenu>

                    <NavLink v-else
                             :href="route('login')">
                        <UserIcon class="text-primary-500 dark:text-primary-400 size-5 xl:hidden" />
                        <span class="hidden xl:inline">Login</span>
                    </NavLink>
                </div>
            </div>
        </nav>

        <main class="flex-grow overflow-y-auto px-3 py-6 md:px-6 xl:px-24 xl:overflow-y-visible" scroll-region>
            <slot />
        </main>
    </div>
</template>
