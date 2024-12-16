<!-- resources/js/Pages/Dashboard/GudangDashboard.vue -->
<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

// Define props for the data we'll need
const props = defineProps({
    stockRequests: {
        type: Array,
        default: () => []
    },
    inventoryItems: {
        type: Array,
        default: () => []
    },
    pendingRequests: {
        type: Number,
        default: 0
    },
    totalInventoryItems: {
        type: Number,
        default: 0
    }
});

const user = computed(() => usePage().props.auth.user);

// Search and filter functionality
const searchQuery = ref('');
const statusFilter = ref('all');
const dateFilter = ref('all');

// Computed requests that includes filtering
const filteredRequests = computed(() => {
    if (!props.stockRequests) return [];
    
    let filtered = [...props.stockRequests];

    // Apply status filter
    if (statusFilter.value !== 'all') {
        filtered = filtered.filter(request => request.status === statusFilter.value);
    }

    // Apply date filter
    if (dateFilter.value !== 'all') {
        const today = new Date();
        const filterDate = new Date();
        
        switch(dateFilter.value) {
            case 'today':
                filtered = filtered.filter(request => 
                    new Date(request.created_at).toDateString() === today.toDateString()
                );
                break;
            case 'week':
                filterDate.setDate(today.getDate() - 7);
                filtered = filtered.filter(request => 
                    new Date(request.created_at) >= filterDate
                );
                break;
            case 'month':
                filterDate.setMonth(today.getMonth() - 1);
                filtered = filtered.filter(request => 
                    new Date(request.created_at) >= filterDate
                );
                break;
        }
    }

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(request => 
            request.outlet?.nama.toLowerCase().includes(query) ||
            request.items.some(item => item.name.toLowerCase().includes(query))
        );
    }

    return filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Formatting functions
const formatDate = date => {
    return new Date(date).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getStatusColor = (status) => {
    return {
        'pending': 'bg-yellow-100 text-yellow-800',
        'approved': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800'
    }[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Gudang Dashboard" />

        <div class="py-6 sm:py-12 bg-white min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
                <!-- Welcome Section -->
                <div class="bg-white p-6 rounded-lg shadow-lg border border-[#CBF3F0] mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Selamat Datang, {{ user.nama }}!
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ formatDate(new Date()) }}
                    </p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Pending Requests Card -->
                    <div class="bg-[#CBF3F0] p-4 sm:p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-700">Pending Requests</h3>
                            <div class="p-2 bg-[#EDD6BE] rounded-full">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-700 mt-2">{{ pendingRequests }}</p>
                    </div>

                    <!-- Total Inventory Items Card -->
                    <div class="bg-[#EDD6BE] p-4 sm:p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-700">Total Inventory Items</h3>
                            <div class="p-2 bg-[#CBF3F0] rounded-full">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-700 mt-2">{{ totalInventoryItems }}</p>
                    </div>
                </div>

                <!-- Recent Stock Requests Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#CBF3F0] mt-6">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4 sm:mb-0">
                                Recent Stock Request
                            </h2>
                            
                            <!-- Filters -->
                            <div class="flex flex-wrap gap-3">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search requests..."
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg"
                                />
                                
                                <select v-model="statusFilter" 
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg">
                                    <option value="all">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>

                                <select v-model="dateFilter"
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg">
                                    <option value="all">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                </select>
                            </div>
                        </div>

                        <!-- Stock Requests Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Outlet</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="request in filteredRequests" :key="request.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ formatDate(request.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ request.outlet?.nama }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div v-for="item in request.items" :key="item.id">
                                                {{ item.name }} - {{ item.amount }} units
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="[
                                                'px-3 py-1 rounded-full text-sm',
                                                getStatusColor(request.status)
                                            ]">
                                                {{ request.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div v-if="request.status === 'pending'" class="flex space-x-2">
                                                <Link :href="route('stock-requests.show', request.id)"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                    View Details
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>