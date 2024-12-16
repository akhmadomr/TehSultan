<!-- resources/js/Pages/Dashboard/CrewOutletDashboard.vue -->
<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';

// Define props properly
const props = defineProps({
    stockReports: {
        type: Array,
        default: () => []
    },
    financialReports: {
        type: Array,
        default: () => []
    },
    allReports: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            meta: {}
        })
    }
});

const user = computed(() => usePage().props.auth.user);

// Search and filter functionality
const searchQuery = ref('');
const statusFilter = ref('all');
const typeFilter = ref('all');

// Computed reports that combines both types
const allReports = computed(() => {
    const combined = [...(props.stockReports || []), ...(props.financialReports || [])];
    return combined.filter(report => {
        const matchesSearch = searchQuery.value === '' || 
            report.outlet?.nama.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesStatus = statusFilter.value === 'all' || report.status === statusFilter.value;
        const matchesType = typeFilter.value === 'all' || report.type === typeFilter.value;
        return matchesSearch && matchesStatus && matchesType;
    }).sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Statistics calculations with props reference
const totalStockReports = computed(() => props.stockReports?.length || 0);
const totalFinancialReports = computed(() => props.financialReports?.length || 0);

// Formatting functions
const formatCurrency = amount => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount || 0);
};

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
        'waiting': 'bg-[#EDD6BE] text-gray-700',
        'validated': 'bg-[#CBF3F0] text-gray-700',
        'rejected': 'bg-red-100 text-red-800'
    }[status] || 'bg-gray-100 text-gray-800';
};

// Sort and filter functionality
const sortBy = ref('date');
const sortOrder = ref('desc');
const reportTypeFilter = ref('all');
const dateFilter = ref('all');

const filteredReports = computed(() => {
    if (!props.allReports?.data) return [];
    
    let filtered = [...props.allReports.data];

    // Apply type filter
    if (reportTypeFilter.value !== 'all') {
        filtered = filtered.filter(report => report.type === reportTypeFilter.value);
    }

    // Apply status filter
    if (statusFilter.value !== 'all') {
        filtered = filtered.filter(report => report.status === statusFilter.value);
    }

    // Apply date filter
    if (dateFilter.value !== 'all') {
        const today = new Date();
        const filterDate = new Date();
        
        switch(dateFilter.value) {
            case 'today':
                filtered = filtered.filter(report => 
                    new Date(report.created_at).toDateString() === today.toDateString()
                );
                break;
            case 'week':
                filterDate.setDate(today.getDate() - 7);
                filtered = filtered.filter(report => 
                    new Date(report.created_at) >= filterDate
                );
                break;
            case 'month':
                filterDate.setMonth(today.getMonth() - 1);
                filtered = filtered.filter(report => 
                    new Date(report.created_at) >= filterDate
                );
                break;
        }
    }

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(report => 
            report.outlet?.nama.toLowerCase().includes(query) ||
            report.type.toLowerCase().includes(query)
        );
    }

    // Apply sorting
    return filtered.sort((a, b) => {
        if (sortOrder.value === 'desc') {
            return new Date(b.created_at) - new Date(a.created_at);
        }
        return new Date(a.created_at) - new Date(b.created_at);
    });
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard Crew Outlet" />

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

                <!-- Statistics Cards - Modified to show only 2 cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Stock Reports Card -->
                    <div class="bg-[#CBF3F0] p-4 sm:p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-700">Stock Reports</h3>
                            <div class="p-2 bg-[#EDD6BE] rounded-full">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-700 mt-2">{{ stockReports.length }}</p>
                    </div>

                    <!-- Financial Reports Card -->
                    <div class="bg-[#EDD6BE] p-4 sm:p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-700">Financial Reports</h3>
                            <div class="p-2 bg-[#CBF3F0] rounded-full">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-700 mt-2">{{ financialReports.length }}</p>
                    </div>
                </div>

                <!-- Reports Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#CBF3F0] mt-6">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4 sm:mb-0">
                                Recent Reports
                            </h2>
                            
                            <!-- Filters -->
                            <div class="flex flex-wrap gap-3">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search reports..."
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg"
                                />
                                
                                <select v-model="statusFilter" 
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg">
                                    <option value="all">All Status</option>
                                    <option value="waiting">Waiting</option>
                                    <option value="validated">Validated</option>
                                    <option value="rejected">Rejected</option>
                                </select>

                                <select v-model="typeFilter"
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg">
                                    <option value="all">All Types</option>
                                    <option value="stock">Stock</option>
                                    <option value="financial">Financial</option>
                                </select>

                                <select v-model="reportTypeFilter" 
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg">
                                    <option value="all">All Types</option>
                                    <option value="stock">Stock</option>
                                    <option value="financial">Financial</option>
                                </select>

                                <select v-model="dateFilter"
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg">
                                    <option value="all">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                </select>

                                <button @click="sortOrder = sortOrder === 'desc' ? 'asc' : 'desc'"
                                    class="px-4 py-2 border-2 border-[#CBF3F0] rounded-lg hover:bg-[#CBF3F0]">
                                    {{ sortOrder === 'desc' ? '↓' : '↑' }} Date
                                </button>
                            </div>
                        </div>

                        <!-- Reports Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Tanggal
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Tipe
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Outlet
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <template v-if="stockReports && financialReports">
                                        <template v-for="report in filteredReports"
                                            :key="report.id">
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ formatDate(report.created_at) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ report.type }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ report.outlet?.nama }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span :class="[
                                                        'px-3 py-1 rounded-full text-sm',
                                                        getStatusColor(report.status)
                                                    ]">
                                                        {{ report.status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                    <tr v-else>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada riwayat report
                                        </td>
                                    </tr>
                                    <tr v-if="filteredReports.length === 0">
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No reports found
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