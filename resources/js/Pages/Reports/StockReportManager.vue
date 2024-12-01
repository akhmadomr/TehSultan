<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
    reports: Object
})

const validateReport = (reportId, status) => {
    router.patch(route('manager.reports.validate', reportId), {
        status: status
    })
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
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#CBF3F0]">
                                    <tr v-for="report in reports.data" :key="report.id" class="hover:bg-[#EDD6BE] transition-colors duration-200">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700">{{ report.user.nama }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700">{{ new Date(report.created_at).toLocaleString() }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700">{{ report.outlet.nama }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-gray-700">{{ report.status }}</td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <div v-if="report.status === 'waiting'" class="space-x-2">
                                                <button 
                                                    @click="validateReport(report.id, 'validated')"
                                                    class="px-4 py-2 bg-[#CBF3F0] text-gray-700 rounded hover:bg-[#EDD6BE] transition-colors duration-200">
                                                    Validate
                                                </button>
                                                <button 
                                                    @click="validateReport(report.id, 'rejected')"
                                                    class="px-4 py-2 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors duration-200">
                                                    Reject
                                                </button>
                                            </div>
                                            <span v-else class="text-gray-700">{{ report.status }}</span>
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