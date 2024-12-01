<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    reports: Object,
    outlets: Array
});

const showModal = ref(false);

// Default items for income
const defaultIncomeItems = [
    { name: 'The', price: 3000 },
    { name: 'Lemon Tea', price: 4000 },
    { name: 'Milktea', price: 5000 },
    { name: 'Freecup', price: 0 },
    { name: 'Pentol', price: 500 },
    { name: 'Tahu', price: 500 },
    { name: 'Urat', price: 1000 },
    { name: 'Puyuh', price: 1000 }
];

// Default items for expenses
const defaultExpenseItems = [
    { name: 'Es Batu', price: 0 },
    { name: 'Gas', price: 0 }
];

const form = ref({
    outlet_id: '',
    shift: '', // Add shift field
    income_items: defaultIncomeItems.map(item => ({
        ...item,
        units_sold: 0,
        total: 0
    })),
    expense_items: defaultExpenseItems.map(item => ({
        ...item,
        units_bought: 0,
        total: 0
    })),
    real_cash: 0
});

const shifts = ['pagi', 'siang']; // Add shifts array

// Computed totals
const totalIncome = computed(() => 
    form.value.income_items.reduce((sum, item) => sum + item.total, 0)
);

const totalExpense = computed(() => 
    form.value.expense_items.reduce((sum, item) => sum + item.total, 0)
);

const netIncome = computed(() => totalIncome.value - totalExpense.value);

const difference = computed(() => netIncome.value - form.value.real_cash);

// Methods for calculations
const calculateIncomeItemTotal = (item) => {
    item.total = item.price * item.units_sold;
};

const calculateExpenseItemTotal = (item) => {
    item.total = item.price * item.units_bought;
};

const addIncomeItem = () => {
    form.value.income_items.push({
        name: '',
        price: 0,
        units_sold: 0,
        total: 0
    });
};

const addExpenseItem = () => {
    form.value.expense_items.push({
        name: '',
        price: 0,
        units_bought: 0,
        total: 0
    });
};

const submit = () => {
    router.post(route('reports.store.financial'), { // Change the route
        outlet_id: form.value.outlet_id,
        shift: form.value.shift,
        income_items: form.value.income_items,
        expense_items: form.value.expense_items,
        real_cash: form.value.real_cash
    }, {
        onSuccess: () => {
            showModal.value = false;
        }
    });
};

const formatNumber = (number) => {
    return new Intl.NumberFormat('id-ID').format(number);
};

// Add these computed methods to calculate totals from financialItems
const getTotalIncome = (report) => {
    return report.financial_items
        ?.filter(item => item.type === 'income')
        ?.reduce((sum, item) => sum + Number(item.total), 0) || 0;
};

const getTotalExpense = (report) => {
    return report.financial_items
        ?.filter(item => item.type === 'expense')
        ?.reduce((sum, item) => sum + Number(item.total), 0) || 0;
};

const getNetIncome = (report) => {
    return getTotalIncome(report) - getTotalExpense(report);
};
</script>

<template>
    <Head title="Financial Reports" />

    <AuthenticatedLayout>
        <div class="py-6 sm:py-12 bg-white min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
                <!-- List of Financial Reports -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#CBF3F0]">
                    <div class="p-4 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-700">Financial Reports</h2>
                            <button @click="showModal = true" class="bg-[#CBF3F0] hover:bg-[#EDD6BE] text-gray-700 px-4 py-2 rounded transition-colors duration-200">
                                New Report
                            </button>
                        </div>

                        <div class="overflow-x-auto -mx-4 sm:mx-0 rounded-lg border border-[#CBF3F0]">
                            <table class="min-w-full divide-y divide-[#CBF3F0]">
                                <thead>
                                    <tr>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Date</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Shift</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Income</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Expense</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Net</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 bg-[#CBF3F0] text-gray-700 font-semibold text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#CBF3F0]">
                                    <tr v-for="report in reports.data" :key="report.id">
                                        <td class="px-6 py-4">{{ new Date(report.created_at).toLocaleDateString() }}</td>
                                        <td class="px-6 py-4">{{ report.shift }}</td>
                                        <td class="px-6 py-4">Rp {{ formatNumber(getTotalIncome(report)) }}</td>
                                        <td class="px-6 py-4">Rp {{ formatNumber(getTotalExpense(report)) }}</td>
                                        <td class="px-6 py-4" :class="getNetIncome(report) < 0 ? 'text-red-500' : 'text-green-500'">
                                            Rp {{ formatNumber(getNetIncome(report)) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span :class="{
                                                'px-2 py-1 rounded-full text-sm': true,
                                                'bg-yellow-100 text-yellow-800': report.status === 'waiting',
                                                'bg-green-100 text-green-800': report.status === 'validated',
                                                'bg-red-100 text-red-800': report.status === 'rejected'
                                            }">
                                                {{ report.status }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal Form -->
                <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-full max-w-7xl shadow-lg rounded-md bg-white">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Outlet and Shift Selection -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Outlet</label>
                                    <select v-model="form.outlet_id" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Select Outlet</option>
                                        <option v-for="outlet in outlets" :key="outlet.id" :value="outlet.id">
                                            {{ outlet.nama }}
                                        </option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Shift</label>
                                    <select v-model="form.shift" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Select Shift</option>
                                        <option v-for="shift in shifts" :key="shift" :value="shift">
                                            {{ shift }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Income Form -->
                            <div class="bg-white p-4 rounded-lg border">
                                <h3 class="text-lg font-medium mb-4">Income Items</h3>
                                <div class="grid grid-cols-4 gap-4 mb-2 font-medium">
                                    <div>Item</div>
                                    <div>Price</div>
                                    <div>Units Sold</div>
                                    <div>Total</div>
                                </div>
                                <div v-for="(item, index) in form.income_items" :key="index" class="grid grid-cols-4 gap-4 mb-2">
                                    <input v-model="item.name" type="text" class="border rounded px-2 py-1">
                                    <input v-model.number="item.price" type="number" class="border rounded px-2 py-1">
                                    <input v-model.number="item.units_sold" type="number" class="border rounded px-2 py-1"
                                           @input="calculateIncomeItemTotal(item)">
                                    <div class="py-1">{{ item.total }}</div>
                                </div>
                                <button type="button" @click="addIncomeItem" class="mt-2 text-blue-500">+ Add Item</button>
                            </div>

                            <!-- Expense Form -->
                            <div class="bg-white p-4 rounded-lg border">
                                <h3 class="text-lg font-medium mb-4">Expense Items</h3>
                                <div class="grid grid-cols-4 gap-4 mb-2 font-medium">
                                    <div>Item</div>
                                    <div>Price</div>
                                    <div>Units Bought</div>
                                    <div>Total</div>
                                </div>
                                <div v-for="(item, index) in form.expense_items" :key="index" class="grid grid-cols-4 gap-4 mb-2">
                                    <input v-model="item.name" type="text" class="border rounded px-2 py-1">
                                    <input v-model.number="item.price" type="number" class="border rounded px-2 py-1">
                                    <input v-model.number="item.units_bought" type="number" class="border rounded px-2 py-1"
                                           @input="calculateExpenseItemTotal(item)">
                                    <div class="py-1">{{ item.total }}</div>
                                </div>
                                <button type="button" @click="addExpenseItem" class="mt-2 text-blue-500">+ Add Item</button>
                            </div>

                            <!-- Summary Form -->
                            <div class="bg-white p-4 rounded-lg border">
                                <h3 class="text-lg font-medium mb-4">Summary</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>Total Income</div>
                                    <div>{{ totalIncome }}</div>
                                    
                                    <div>Total Expense</div>
                                    <div>{{ totalExpense }}</div>
                                    
                                    <div>Net Income</div>
                                    <div>{{ netIncome }}</div>
                                    
                                    <div>Real Cash</div>
                                    <div>
                                        <input v-model.number="form.real_cash" type="number" class="border rounded px-2 py-1">
                                    </div>
                                    
                                    <div>Difference</div>
                                    <div :class="difference < 0 ? 'text-red-500' : 'text-green-500'">
                                        {{ difference }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" @click="showModal = false" 
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                    Submit Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>