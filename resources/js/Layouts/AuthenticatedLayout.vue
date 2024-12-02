<script setup>
import { computed, ref } from 'vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link, usePage } from '@inertiajs/vue3'

const userRole = computed(() => usePage().props.auth.user.role)
const showReportsMenu = ref(false)
const showingNavigationDropdown = ref(false)

// Define navigation menus per role
const navigationMenus = {
    manager: [
        { name: 'Dashboard', route: 'manager.dashboard' },
        { name: 'Users Management', route: 'manager.users.index' },
        { 
            name: 'Reports',
            children: [
                { name: 'Stock Reports', route: 'manager.reports.stock' },
                { name: 'Financial Reports', route: 'manager.reports.financial' }
            ]
        }
    ],
    crewoutlet: [
        { name: 'Dashboard', route: 'crew.dashboard' },
        { name: 'Stock', route: 'stock.index' },
        { name: 'Financial', route: 'financial.reports' }
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
        <div class="min-h-screen bg-[#EDD6BE]">
            <nav class="bg-[#EDD6BE] border-b border-orange-200 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo class="h-14 object-contain text-[#FFFFFF]" />
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden sm:flex sm:items-center sm:space-x-4 lg:space-x-8">
                            <template v-for="item in navigationMenus[userRole]" :key="item.name">
                                <template v-if="!item.children">
                                    <NavLink :href="route(item.route)" :active="route().current(item.route)" 
                                        class="text-gray-900 hover:text-orange-700 border-b-2 border-transparent hover:border-orange-400 transition-all duration-150">
                                        {{ item.name }}
                                    </NavLink>
                                </template>
                                <div v-else class="relative flex items-center" @mouseenter="showReportsMenu = true" @mouseleave="showReportsMenu = false">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-900 hover:text-orange-700 hover:border-orange-400 focus:outline-none transition-all duration-150 h-full">
                                        {{ item.name }}
                                    </button>
                                    <div v-show="showReportsMenu" class="absolute left-0 top-[100%] z-50 w-48 rounded-md shadow-lg bg-white ring-1 ring-orange-200">
                                        <div class="py-1">
                                            <Link v-for="child in item.children" 
                                                  :key="child.name"
                                                  :href="route(child.route)"
                                                  class="block px-4 py-2 text-sm text-gray-900 hover:text-orange-700 hover:bg-orange-50">
                                                {{ child.name }}
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- User Dropdown and Logout Button -->
                        <div class="hidden sm:flex sm:items-center sm:space-x-4">
                            <Dropdown align="right" width="48" class="relative">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-900 hover:text-orange-700 focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.auth.user.name }}
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')" class="text-gray-900 hover:text-orange-700 hover:bg-orange-50">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button" class="text-gray-900 hover:text-orange-700 hover:bg-orange-50">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                            
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="hidden lg:inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-800 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Logout
                            </Link>
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-orange-700 focus:outline-none"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div
                    :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            v-for="item in navigationMenus[userRole]"
                            :key="item.name"
                            :href="item.route ? route(item.route) : '#'"
                            :active="item.route ? route().current(item.route) : false"
                            class="text-gray-900 hover:text-orange-700"
                        >
                            {{ item.name }}
                        </ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="min-h-screen">
                <slot />
            </main>
        </div>
    </div>
</template>