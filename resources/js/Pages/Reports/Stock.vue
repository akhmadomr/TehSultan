<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    reports: {
        type: Object,
        required: true
    },
    defaultItems: {
        type: Array,
        required: true
    },
    outlets: {
        type: Array,
        required: true,
        default: () => []
    }
});

const showModal = ref(false);
const errorMessage = ref('');
const statuses = ['completed', 'pending', 'cancelled'];

const form = ref({
    outlet_id: '',
    shift: '',  // Add this
    items: []
});

const shifts = ['pagi', 'siang'];  // Add this

// Initialize form with all default items
const initializeForm = () => {
    form.value.items = props.defaultItems.map(item => ({
        item_name: item.name,
        initial_stock: 0,
        added_stock: 0,
        total_stock: 0,
        remaining_stock: 0,
        used_stock: 0,
        status: 'pending'
    }));
};

const calculateTotalAndUsed = (item) => {
    item.total_stock = Number(item.initial_stock) + Number(item.added_stock);
    item.used_stock = item.total_stock - Number(item.remaining_stock);
};

// Add debugging logs
onMounted(() => {
    console.log('Outlets received:', props.outlets);
    console.log('Reports data:', props.reports);
    console.log('First report user:', props.reports.data[0]?.user);
});

const openModal = () => {
    console.log('Opening modal with outlets:', props.outlets);
    initializeForm();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const markAllAsCompleted = () => {
    form.value.items.forEach(item => {
        item.status = 'completed';
    });
};

const validateForm = () => {
    if (!form.value.outlet_id) {
        errorMessage.value = 'Please select an outlet';
        return false;
    }
    if (!form.value.shift) {  // Add this validation
        errorMessage.value = 'Please select a shift';
        return false;
    }
    const pendingItems = form.value.items.filter(item => item.status !== 'completed');
    if (pendingItems.length > 0) {
        errorMessage.value = 'All items must be marked as completed before submitting';
        return false;
    }
    errorMessage.value = '';
    return true;
};

const submit = () => {
    console.log('Submitting form with outlet_id:', form.value.outlet_id, 'shift:', form.value.shift);
    if (!validateForm()) {
        return;
    }
    
    router.post(route('stock.store'), {
        outlet_id: form.value.outlet_id,
        shift: form.value.shift, // Add this line to include shift in submission
        items: form.value.items
    }, {
        onSuccess: () => {
            closeModal();
            form.value.items = [];
            form.value.shift = ''; // Reset shift
            form.value.outlet_id = ''; // Reset outlet
        },
        onError: (errors) => {
            console.error('Submission errors:', errors);
            errorMessage.value = Object.values(errors).join(', ');
        }
    });
};

const getStatusColor = (status) => {
    return {
        'bg-green-100 text-green-800': status === 'completed',
        'bg-orange-100 text-orange-800': status === 'pending',
        'bg-red-100 text-red-800': status === 'cancelled'
    }
};
</script>

<template>
    <Head title="Stock Reports" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold">Stock Reports</h2>
                            <button @click="openModal" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                New Report
                            </button>
                        </div>

                        <!-- Reports Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="report in reports.data" :key="report.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                            {{ report.user ? report.user.name || report.user.nama : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ new Date(report.created_at).toLocaleDateString() }}
                                            <span class="text-gray-500 text-sm ml-2">
                                                {{ new Date(report.created_at).toLocaleTimeString() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-sm rounded-full" :class="getStatusColor(report.status)">
                                                {{ report.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button 
                                                class="text-blue-600 hover:text-blue-800"
                                                @click="editReport(report.id)"
                                            >
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Modal -->
                        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                            <div class="relative top-20 mx-auto p-5 border w-full max-w-7xl shadow-lg rounded-md bg-white">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-bold">New Stock Report</h3>
                                    <button @click="closeModal" class="text-gray-600 hover:text-gray-800">
                                        <span class="text-2xl">&times;</span>
                                    </button>
                                </div>

                                <form @submit.prevent="submit" class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <div class="flex space-x-4 bg-orange text-black">
                                            <div class="relative">
                                                <select 
                                                    v-model="form.outlet_id" 
                                                    class="border rounded px-3 py-2 w-48 cursor-pointer bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                    :class="{ 'border-red-500': errorMessage && !form.outlet_id }"
                                                >
                                                    <option value="" disabled class="text-gray-500">Select Outlet</option>
                                                    <option 
                                                        v-for="outlet in outlets" 
                                                        :key="outlet.id" 
                                                        :value="outlet.id"
                                                        class="hover:bg-blue-50 hover:text-blue-700 py-2 px-4"
                                                        style="background-color: white; color: black;"
                                                    >
                                                        {{ outlet.nama }}
                                                    </option>
                                                </select>
                                                <!-- Debug info -->
                                                <div v-if="!outlets || outlets.length === 0" class="text-red-500 text-sm mt-1">
                                                    No outlets available
                                                </div>
                                                <div class="text-gray-500 text-sm mt-1">
                                                    Selected outlet: {{ form.outlet_id || 'None' }}
                                                </div>
                                            </div>
                                            <div class="relative ml-4">
                                                <select 
                                                    v-model="form.shift" 
                                                    class="border rounded px-3 py-2 w-32 cursor-pointer bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                    :class="{ 'border-red-500': errorMessage && !form.shift }"
                                                >
                                                    <option value="" disabled>Select Shift</option>
                                                    <option 
                                                        v-for="shift in shifts" 
                                                        :key="shift" 
                                                        :value="shift"
                                                        class="py-2 px-4"
                                                    >
                                                        {{ shift }}
                                                    </option>
                                                </select>
                                            </div>
                                            <button type="button" 
                                                    @click="markAllAsCompleted"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                                Mark All as Completed
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Error Message -->
                                    <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                        {{ errorMessage }}
                                    </div>

                                    <!-- Table Header -->
                                    <div class="grid grid-cols-7 gap-4 bg-gray-100 p-2 rounded-t-lg">
                                        <div class="font-semibold">Item Name</div>
                                        <div class="font-semibold">Initial Stock</div>
                                        <div class="font-semibold">Added Stock</div>
                                        <div class="font-semibold">Total Stock</div>
                                        <div class="font-semibold">Remaining Stock</div>
                                        <div class="font-semibold">Used Stock</div>
                                        <div class="font-semibold">Status</div>
                                    </div>

                                    <!-- Table Body -->
                                    <div class="max-h-[60vh] overflow-y-auto">
                                        <div v-for="(item, index) in form.items" :key="index" 
                                             class="grid grid-cols-7 gap-4 py-2 border-b">
                                            <div class="font-medium">{{ item.item_name }}</div>
                                            <input type="number" v-model="item.initial_stock" 
                                                   class="border rounded px-2 py-1" @input="calculateTotalAndUsed(item)">
                                            <input type="number" v-model="item.added_stock" 
                                                   class="border rounded px-2 py-1 bg-gray-100" disabled>
                                            <div class="py-1">{{ item.total_stock }}</div>
                                            <input type="number" v-model="item.remaining_stock" 
                                                   class="border rounded px-2 py-1" @input="calculateTotalAndUsed(item)">
                                            <div class="py-1">{{ item.used_stock }}</div>
                                            <select v-model="item.status" 
                                                    class="border rounded px-2 py-1"
                                                    :class="getStatusColor(item.status)">
                                                <option v-for="status in statuses" :key="status" :value="status">
                                                    {{ status }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex justify-end space-x-2 mt-4">
                                        <button type="button" @click="closeModal" 
                                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                            Cancel
                                        </button>
                                        <button type="submit" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                            Submit Report
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>