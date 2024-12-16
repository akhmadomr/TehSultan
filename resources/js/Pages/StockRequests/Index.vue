<template>
    <Head title="Stock Requests" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Stock Requests</h2>
                        <button
                            @click="showingModal = true"
                            class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700"
                        >
                            Create Request
                        </button>
                    </div>

                    <!-- Stock Request Modal -->
                    <Modal :show="showingModal" @close="showingModal = false">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">
                                Create Stock Request
                            </h2>

                            <form @submit.prevent="createRequest" class="space-y-4">
                                <div class="grid grid-cols-3 gap-4">
                                    <!-- Outlet Selection -->
                                    <div>
                                        <InputLabel for="outlet" value="Outlet" />
                                        <select
                                            v-model="form.outlet_id"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                            :class="{ 'border-red-500': errorMessage && !form.outlet_id }"
                                        >
                                            <option value="">Select Outlet</option>
                                            <option v-for="outlet in outlets" :key="outlet.id" :value="outlet.id">
                                                {{ outlet.nama }}
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Shift Selection -->
                                    <div>
                                        <InputLabel for="shift" value="Shift" />
                                        <select
                                            v-model="form.shift"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                            :class="{ 'border-red-500': errorMessage && !form.shift }"
                                        >
                                            <option value="">Select Shift</option>
                                            <option v-for="shift in shifts" :key="shift" :value="shift">
                                                {{ shift }}
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Date Selection -->
                                    <div>
                                        <InputLabel for="date" value="Date" />
                                        <TextInput
                                            type="date"
                                            v-model="form.date"
                                            class="mt-1 block w-full"
                                        />
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                    {{ errorMessage }}
                                </div>

                                <!-- Table Header -->
                                <div class="grid grid-cols-2 gap-4 bg-gray-100 p-2 rounded-t-lg mt-4">
                                    <div class="font-semibold">Item Name</div>
                                    <div class="font-semibold">Request Amount</div>
                                </div>

                                <!-- Table Body -->
                                <div class="max-h-[60vh] overflow-y-auto">
                                    <div v-for="item in form.items" :key="item.id" 
                                         class="grid grid-cols-2 gap-4 py-2 border-b">
                                        <!-- Debug info -->
                                        <div class="font-medium">
                                            {{ item.name }}
                                            <span class="text-xs text-gray-500">(ID: {{ item.id }})</span>
                                        </div>
                                        <input type="number" 
                                               v-model="item.amount" 
                                               class="border rounded px-2 py-1"
                                               min="0">
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" @click="showingModal = false" 
                                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded"
                                            :disabled="form.processing">
                                        Submit Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </Modal>

                    <!-- Table of stock requests here -->
                    <!-- Add your table implementation -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref, watch, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
    stockRequests: Array,
    outlets: Array,
    stockItems: {
        type: Array,
        required: true
    }
});

// Add these console logs
console.log('Stock Items received:', props.stockItems);
console.log('Outlets received:', props.outlets);

onMounted(() => {
    console.log('Component mounted, stockItems:', props.stockItems);
});

const form = useForm({
    outlet_id: '',
    shift: '',
    date: new Date().toISOString().substr(0, 10),
    items: []  // Initialize as empty array
});

// Add watch effect to initialize items when modal opens
const initializeItems = () => {
    console.log('Initializing items with stockItems:', props.stockItems);
    form.items = props.stockItems.map(item => {
        const mappedItem = {
            id: parseInt(item.id),
            name: item.name,
            amount: 0
        };
        console.log('Mapped item:', mappedItem);
        return mappedItem;
    });
    console.log('Final form items:', form.items);
};

// Update showingModal ref to trigger items initialization
const showingModal = ref(false);
watch(showingModal, (newValue) => {
    if (newValue) {
        console.log('Initializing items with:', props.stockItems);
        initializeItems();
    }
});

const errorMessage = ref('')
const shifts = ['pagi', 'siang']

const resetForm = () => {
    form.outlet_id = '';
    form.shift = '';
    form.date = new Date().toISOString().substr(0, 10);
    initializeItems();
};

// Update validateForm to check for items
const validateForm = () => {
    if (!form.outlet_id) {
        errorMessage.value = 'Please select an outlet';
        return false;
    }
    if (!form.shift) {
        errorMessage.value = 'Please select a shift';
        return false;
    }
    if (!form.items.some(item => item.amount > 0)) {
        errorMessage.value = 'Please enter at least one item amount';
        return false;
    }
    errorMessage.value = '';
    return true;
};

const createRequest = () => {
    if (!validateForm()) return;

    // Format the data properly before sending
    const formData = {
        outlet_id: parseInt(form.outlet_id),
        shift: form.shift,
        date: form.date,
        items: form.items
            .filter(item => item.amount > 0)
            .map(item => ({
                id: parseInt(item.id),
                amount: parseInt(item.amount)
            }))
    };

    console.log('Sending formatted data:', formData);

    form.post(route('stock-requests.store'), formData, {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Request submitted successfully');
            resetForm();
            showingModal.value = false;
        },
        onError: (errors) => {
            console.error('Submission errors:', errors);
            errorMessage.value = typeof errors === 'string' 
                ? errors 
                : Object.values(errors).join(', ');
        }
    });
};
</script>