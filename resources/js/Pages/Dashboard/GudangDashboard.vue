<!-- resources/js/Pages/Dashboard/GudangDashboard.vue -->
<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Gudang Dashboard" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Gudang Dashboard</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>