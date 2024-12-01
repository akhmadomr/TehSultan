<!-- resources/js/Pages/Dashboard/ManagerDashboard.vue -->
<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    activeUsers: Number,
    financialSummary: Object,
    latestReports: Array
});

// Search and filter states
const searchQuery = ref('');
const statusFilter = ref('all');
const typeFilter = ref('all');
const outletFilter = ref('all');

// Binary search implementation
const binarySearch = (arr, query) => {
    let left = 0;
    let right = arr.length - 1;
    
    while (left <= right) {
        const mid = Math.floor((left + right) / 2);
        if (arr[mid].toLowerCase().includes(query.toLowerCase())) return true;
        if (arr[mid].toLowerCase() < query.toLowerCase()) left = mid + 1;
        else right = mid - 1;
    }
    return false;
};

// Filtered reports
const filteredReports = computed(() => {
    return props.latestReports.filter(report => {
        const matchesSearch = searchQuery.value === '' || 
            binarySearch([report.type, report.outlet.nama, report.status], searchQuery.value);
        const matchesStatus = statusFilter.value === 'all' || report.status === statusFilter.value;
        const matchesType = typeFilter.value === 'all' || report.type === typeFilter.value;
        const matchesOutlet = outletFilter.value === 'all' || report.outlet.id === outletFilter.value;
        
        return matchesSearch && matchesStatus && matchesType && matchesOutlet;
    });
});

// Chart data
const chartData = {
    labels: ['Income', 'Expense', 'Net Total'],
    datasets: [{
        label: 'Financial Overview',
        data: [
            props.financialSummary.income,
            props.financialSummary.expense,
            props.financialSummary.netTotal
        ],
        backgroundColor: ['rgba(34, 197, 94, 0.2)', 'rgba(239, 68, 68, 0.2)', 'rgba(59, 130, 246, 0.2)'],
        borderColor: ['rgb(34, 197, 94)', 'rgb(239, 68, 68)', 'rgb(59, 130, 246)'],
        borderWidth: 2
    }]
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false
};

// Format number to Rupiah
const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(number);
};

const getStatusColor = (status) => {
    switch (status) {
        case 'validated': return 'bg-[#CBF3F0] text-gray-700 min-w-[100px] text-center';
        case 'waiting': return 'bg-[#EDD6BE] text-gray-700 min-w-[100px] text-center';
        case 'rejected': return 'bg-red-100 text-red-600 min-w-[100px] text-center';
        default: return 'bg-[#CBF3F0] text-gray-700 min-w-[100px] text-center';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Manager Dashboard" />
        <div class="py-6 sm:py-12 bg-white min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Active Users Card -->
                    <div class="bg-[#CBF3F0] p-4 sm:p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-700">Active Users</h3>
                            <div class="p-2 bg-[#EDD6BE] rounded-full">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-700">{{ activeUsers }}</p>
                    </div>

                    <!-- Income Card -->
                    <div class="bg-[#EDD6BE] p-4 sm:p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-700">Total Income</h3>
                            <div class="p-2 bg-[#CBF3F0] rounded-full">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-700">{{ formatRupiah(financialSummary.income) }}</p>
                    </div>

                    <!-- Expense Card -->
                    <div class="bg-[#CBF3F0] p-4 sm:p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-700">Total Expense</h3>
                            <div class="p-2 bg-[#EDD6BE] rounded-full">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-700">{{ formatRupiah(financialSummary.expense) }}</p>
                    </div>

                    <!-- Net Total Card -->
                    <div class="bg-[#EDD6BE] p-4 sm:p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-700">Net Total</h3>
                            <div class="p-2 bg-[#CBF3F0] rounded-full">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-700">{{ formatRupiah(financialSummary.netTotal) }}</p>
                    </div>
                </div>

                <!-- Reports Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#CBF3F0]">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-6">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-700">Latest Reports</h2>
                            
                            <!-- Updated Filters -->
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search reports..."
                                    class="w-full sm:w-auto px-3 sm:px-4 py-2 bg-white border-2 border-[#CBF3F0] rounded-lg focus:ring-2 focus:ring-[#EDD6BE] text-gray-700 placeholder-gray-400 focus:outline-none"
                                />
                                
                                <div class="grid grid-cols-2 sm:flex gap-3 sm:gap-4">
                                    <select 
                                        v-model="statusFilter" 
                                        class="w-full px-3 sm:px-4 py-2 bg-white border-2 border-[#CBF3F0] rounded-lg focus:ring-2 focus:ring-[#EDD6BE] text-gray-700 focus:outline-none"
                                    >
                                        <option value="all">All Status</option>
                                        <option value="waiting">Waiting</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="validated">Validated</option>
                                    </select>

                                    <select 
                                        v-model="typeFilter" 
                                        class="w-full px-3 sm:px-4 py-2 bg-white border-2 border-[#CBF3F0] rounded-lg focus:ring-2 focus:ring-[#EDD6BE] text-gray-700 focus:outline-none"
                                    >
                                        <option value="all">All Types</option>
                                        <option value="stock">Stock</option>
                                        <option value="financial">Financial</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Reports Table -->
                        <div class="overflow-x-auto -mx-4 sm:mx-0 rounded-lg border border-[#CBF3F0]">
                            <table class="min-w-full divide-y divide-[#CBF3F0]">
                                <thead>
                                    <tr>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Date</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Type</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Outlet</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#CBF3F0]">
                                    <tr v-for="report in filteredReports" 
                                        :key="report.id"
                                        class="hover:bg-[#EDD6BE] transition-colors duration-200">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700 whitespace-nowrap">
                                            {{ new Date(report.created_at).toLocaleDateString() }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700 whitespace-nowrap">{{ report.type }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700 whitespace-nowrap">{{ report.outlet.nama }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <span :class="[
                                                'px-4 py-2 rounded-full text-sm font-medium inline-block',
                                                getStatusColor(report.status)
                                            ]">
                                                {{ report.status }}
                                            </span>
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