
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
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Stock Reports</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th>Submitted By</th>
                                    <th>Time</th>
                                    <th>Outlet</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="report in reports.data" :key="report.id">
                                    <td>{{ report.user.nama }}</td>
                                    <td>{{ new Date(report.created_at).toLocaleString() }}</td>
                                    <td>{{ report.outlet.nama }}</td>
                                    <td>{{ report.status }}</td>
                                    <td>
                                        <div v-if="report.status === 'waiting'" class="space-x-2">
                                            <button 
                                                @click="validateReport(report.id, 'validated')"
                                                class="px-4 py-2 bg-green-500 text-white rounded">
                                                Validate
                                            </button>
                                            <button 
                                                @click="validateReport(report.id, 'rejected')"
                                                class="px-4 py-2 bg-red-500 text-white rounded">
                                                Reject
                                            </button>
                                        </div>
                                        <span v-else>{{ report.status }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>