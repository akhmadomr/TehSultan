<script setup>
import { computed } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link, usePage } from '@inertiajs/vue3'

const userRole = computed(() => usePage().props.auth.user.role)

// Define navigation menus per role
const navigationMenus = {
    manager: [
        { name: 'Dashboard', route: 'manager.dashboard' },
        { name: 'Users Management', route: 'manager.users.index' },
        { name: 'Reports', route: 'reports.index' }
    ],
    crewoutlet: [
        { name: 'Dashboard', route: 'crew.dashboard' },
        { name: 'Stock', route: 'stock.index' },
        { name: 'Sales', route: 'sales.index' }
    ],
    gudang: [
        { name: 'Dashboard', route: 'gudang.dashboard' },
        { name: 'Inventory', route: 'inventory.index' },
        { name: 'Stock Requests', route: 'stock-requests.index' }
    ]
}
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <template v-for="item in navigationMenus[userRole]" :key="item.name">
                                <NavLink :href="route(item.route)" :active="route().current(item.route)">
                                    {{ item.name }}
                                </NavLink>
                            </template>
                        </div>

                        <!-- User Dropdown and Logout Button -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.auth.user.name }}
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                            
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>