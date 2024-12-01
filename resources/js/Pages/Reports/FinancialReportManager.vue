<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    reports: Object
});

const selectedReport = ref(null);
const showDetails = ref(false);

const formatDate = (date) => {
    return new Date(date).toLocaleString();
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
};

const viewDetails = (report) => {
    selectedReport.value = report;
    showDetails.value = true;
    
    // Add logging to check the data
    console.log('Selected Report:', report);
    console.log('Financial Items:', report.financial_items);
    if (report.financial_items?.length > 0) {
        console.log('Sample Calculations:', {
            income: calculateTotal('income'),
            expense: calculateTotal('expense'),
            net: calculateTotal('income') - calculateTotal('expense')
        });
    }
};

const updateStatus = async (reportId, status) => {
    try {
        // Add debug logging
        console.log('Route:', route('manager.reports.validate', reportId));
        console.log('Updating report', reportId, 'with status', status);

        const response = await axios.patch(route('manager.reports.validate', reportId), {
            status: status
        });

        console.log('Update response:', response);
        window.location.reload();
    } catch (error) {
        console.error('Error updating report status:', error);
        console.error('Response:', error.response?.data);
        alert(`Failed to update report status: ${error.response?.data?.message || error.message}`);
    }
};

const calculateTotal = (type) => {
    if (!selectedReport.value?.financial_items) {
        console.log('No financial items found');
        return 0;
    }
    
    const items = selectedReport.value.financial_items.filter(i => i.type === type);
    console.log(`${type} items:`, items);
    
    const total = items.reduce((sum, item) => {
        const itemTotal = parseFloat(item.total || 0);
        console.log(`Item ${item.item_name}: ${itemTotal}`);
        return sum + itemTotal;
    }, 0);
    
    console.log(`Total ${type}:`, total);
    return total;
};
</script>

<template>
    <Head title="Financial Reports" />

    <AuthenticatedLayout>
        <div class="py-6 sm:py-12 bg-white min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#CBF3F0]">
                    <div class="p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4">Financial Reports</h2>
                        <div class="overflow-x-auto -mx-4 sm:mx-0 rounded-lg border border-[#CBF3F0]">
                            <table class="min-w-full divide-y divide-[#CBF3F0]">
                                <thead>
                                    <tr>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Date</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Outlet</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Shift</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Status</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#CBF3F0]">
                                    <tr v-for="report in reports.data" :key="report.id">
                                        <td class="px-6 py-4 border-b">{{ formatDate(report.created_at) }}</td>
                                        <td class="px-6 py-4 border-b">{{ report.outlet?.nama }}</td>
                                        <td class="px-6 py-4 border-b capitalize">{{ report.shift }}</td>
                                        <td class="px-6 py-4 border-b">
                                            <span :class="{
                                                'px-2 py-1 rounded text-sm': true,
                                                'bg-yellow-100 text-yellow-800': report.status === 'waiting',
                                                'bg-green-100 text-green-800': report.status === 'validated',
                                                'bg-red-100 text-red-800': report.status === 'rejected'
                                            }">
                                                {{ report.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b">
                                            <button @click="viewDetails(report)" 
                                                    class="text-blue-600 hover:text-blue-800 mr-2">
                                                View Details
                                            </button>
                                            <button v-if="report.status === 'waiting'"
                                                    @click="updateStatus(report.id, 'validated')"
                                                    class="text-green-600 hover:text-green-800 mr-2">
                                                Validate
                                            </button>
                                            <button v-if="report.status === 'waiting'"
                                                    @click="updateStatus(report.id, 'rejected')"
                                                    class="text-red-600 hover:text-red-800">
                                                Reject
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Details Modal -->
        <div v-if="showDetails" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Report Details</h3>
                        <button @click="showDetails = false" class="text-gray-500 hover:text-gray-700">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>
                    
                    <div v-if="selectedReport" class="space-y-6">
                        <!-- Basic Information -->
                        <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold mb-3 text-gray-700">Report Information</h4>
                                <p class="mb-2"><strong>Report ID:</strong> #{{ selectedReport.id }}</p>
                                <p class="mb-2"><strong>Outlet:</strong> {{ selectedReport.outlet?.nama }}</p>
                                <p class="mb-2"><strong>Created By:</strong> {{ selectedReport.user?.name }}</p>
                                <p class="mb-2"><strong>Date:</strong> {{ formatDate(selectedReport.created_at) }}</p>
                                <p class="mb-2"><strong>Shift:</strong> {{ selectedReport.shift }}</p>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-3 text-gray-700">Status Information</h4>
                                <p class="mb-2">
                                    <strong>Status:</strong> 
                                    <span :class="{
                                        'px-2 py-1 rounded text-sm': true,
                                        'bg-yellow-100 text-yellow-800': selectedReport.status === 'waiting',
                                        'bg-green-100 text-green-800': selectedReport.status === 'validated',
                                        'bg-red-100 text-red-800': selectedReport.status === 'rejected'
                                    }">
                                        {{ selectedReport.status }}
                                    </span>
                                </p>
                                <template v-if="selectedReport.validated_by">
                                    <p class="mb-2"><strong>Validated By:</strong> {{ selectedReport.validator?.name }}</p>
                                    <p class="mb-2"><strong>Validated At:</strong> {{ formatDate(selectedReport.validated_at) }}</p>
                                </template>
                            </div>
                        </div>

                        <!-- Financial Summary with Debug Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold mb-3 text-gray-700">Financial Summary</h4>
                            <!-- Add debug info -->
                            <pre v-if="selectedReport?.financial_items" class="text-xs mb-4 p-2 bg-gray-100 rounded">
                                Items count: {{ selectedReport.financial_items.length }}
                                Income items: {{ selectedReport.financial_items.filter(i => i.type === 'income').length }}
                                Expense items: {{ selectedReport.financial_items.filter(i => i.type === 'expense').length }}
                            </pre>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-green-50 p-3 rounded">
                                    <p class="text-sm text-green-600">Total Income</p>
                                    <p class="text-lg font-semibold text-green-700">
                                        {{ formatCurrency(calculateTotal('income')) }}
                                    </p>
                                </div>
                                <div class="bg-red-50 p-3 rounded">
                                    <p class="text-sm text-red-600">Total Expense</p>
                                    <p class="text-lg font-semibold text-red-700">
                                        {{ formatCurrency(calculateTotal('expense')) }}
                                    </p>
                                </div>
                                <div class="bg-blue-50 p-3 rounded">
                                    <p class="text-sm text-blue-600">Net Income</p>
                                    <p class="text-lg font-semibold text-blue-700">
                                        {{ formatCurrency(calculateTotal('income') - calculateTotal('expense')) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Items -->
                        <div v-if="selectedReport.financial_items?.length" class="space-y-4">
                            <!-- Income Items Table -->
                            <div class="border rounded-lg overflow-hidden">
                                <h5 class="font-medium text-green-600 bg-green-50 p-3 border-b">Income Items</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr v-for="item in selectedReport.financial_items.filter(i => i.type === 'income')"
                                                :key="item.id"
                                                class="hover:bg-gray-50">
                                                <td class="px-6 py-4">{{ item.item_name }}</td>
                                                <td class="px-6 py-4 text-right">{{ formatCurrency(item.price) }}</td>
                                                <td class="px-6 py-4 text-right">{{ item.quantity }}</td>
                                                <td class="px-6 py-4 text-right">{{ formatCurrency(item.total) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Expense Items Table -->
                            <div class="border rounded-lg overflow-hidden">
                                <h5 class="font-medium text-red-600 bg-red-50 p-3 border-b">Expense Items</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr v-for="item in selectedReport.financial_items.filter(i => i.type === 'expense')"
                                                :key="item.id"
                                                class="hover:bg-gray-50">
                                                <td class="px-6 py-4">{{ item.item_name }}</td>
                                                <td class="px-6 py-4 text-right">{{ formatCurrency(item.price) }}</td>
                                                <td class="px-6 py-4 text-right">{{ item.quantity }}</td>
                                                <td class="px-6 py-4 text-right">{{ formatCurrency(item.total) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div v-if="selectedReport.status === 'waiting'" class="flex justify-end space-x-3 pt-4 border-t">
                            <button @click="updateStatus(selectedReport.id, 'validated')"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Validate Report
                            </button>
                            <button @click="updateStatus(selectedReport.id, 'rejected')"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Reject Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>