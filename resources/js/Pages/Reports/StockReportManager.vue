<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    reports: Object
})

const selectedReport = ref(null)
const showDetails = ref(false)

const viewDetails = (report) => {
    selectedReport.value = report
    showDetails.value = true
}

const validateReport = (reportId, status) => {
    router.patch(route('manager.reports.validate', reportId), {
        status: status
    })
}

const formatDate = (date) => {
    return new Date(date).toLocaleString()
}
</script>

<template>
    <Head title="Stock Reports" />

    <AuthenticatedLayout>
        <div class="py-6 sm:py-12 bg-white min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#CBF3F0]">
                    <div class="p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4">Stock Reports</h2>
                        <div class="overflow-x-auto -mx-4 sm:mx-0 rounded-lg border border-[#CBF3F0]">
                            <table class="min-w-full divide-y divide-[#CBF3F0]">
                                <thead>
                                    <tr>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Submitted By</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Time</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Outlet</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Status</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Validation Messages</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#CBF3F0]">
                                    <tr v-for="report in reports.data" :key="report.id" class="hover:bg-[#EDD6BE] transition-colors duration-200">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700">{{ report.user.nama }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700">{{ formatDate(report.created_at) }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700">{{ report.outlet.nama }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <span :class="{
                                                'px-2 py-1 rounded text-sm': true,
                                                'bg-yellow-100 text-yellow-800': report.status === 'waiting',
                                                'bg-green-100 text-green-800': report.status === 'validated',
                                                'bg-red-100 text-red-800': report.status === 'rejected'
                                            }">
                                                {{ report.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <div v-if="report.validation_messages?.length" class="text-yellow-600">
                                                <div v-for="(message, index) in report.validation_messages" 
                                                     :key="index"
                                                     class="text-sm bg-yellow-50 p-2 rounded mb-1">
                                                    {{ message }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <button @click="viewDetails(report)" 
                                                    class="text-blue-600 hover:text-blue-800 mr-2">
                                                View Details
                                            </button>
                                            <template v-if="report.status === 'waiting'">
                                                <button 
                                                    @click="validateReport(report.id, 'validated')"
                                                    class="text-green-600 hover:text-green-800 mr-2">
                                                    Validate
                                                </button>
                                                <button 
                                                    @click="validateReport(report.id, 'rejected')"
                                                    class="text-red-600 hover:text-red-800">
                                                    Reject
                                                </button>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Report Details Modal -->
        <div v-if="showDetails" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Stock Report Details</h3>
                        <button @click="showDetails = false" class="text-gray-500 hover:text-gray-700">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>
                    
                    <div v-if="selectedReport" class="space-y-6">
                        <!-- Report Information -->
                        <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold mb-3 text-gray-700">Report Information</h4>
                                <p class="mb-2"><strong>Report ID:</strong> #{{ selectedReport.id }}</p>
                                <p class="mb-2"><strong>Outlet:</strong> {{ selectedReport.outlet.nama }}</p>
                                <p class="mb-2"><strong>Submitted By:</strong> {{ selectedReport.user.nama }}</p>
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

                        <!-- Stock Items Table -->
                        <div class="border rounded-lg overflow-hidden">
                            <h4 class="font-semibold p-4 bg-gray-50 border-b">Stock Items</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Name</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Initial Stock</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Added Stock</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Stock</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Used Stock</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Remaining Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="item in selectedReport.items" :key="item.id" class="hover:bg-gray-50">
                                            <td class="px-6 py-4">{{ item.item_name }}</td>
                                            <td class="px-6 py-4 text-right">{{ item.initial_stock }}</td>
                                            <td class="px-6 py-4 text-right">{{ item.added_stock }}</td>
                                            <td class="px-6 py-4 text-right">{{ item.total_stock }}</td>
                                            <td class="px-6 py-4 text-right">{{ item.used_stock }}</td>
                                            <td class="px-6 py-4 text-right">{{ item.remaining_stock }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div v-if="selectedReport.status === 'waiting'" class="flex justify-end space-x-3 pt-4 border-t">
                            <button @click="validateReport(selectedReport.id, 'validated')"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Validate Report
                            </button>
                            <button @click="validateReport(selectedReport.id, 'rejected')"
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