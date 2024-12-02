<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    username: '',
    role: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        
        <!-- Main container for login page -->
        <div class="login-container min-h-screen flex justify-center items-center bg-white">
            <!-- Login Box (Fixed size with bigger width) -->
            <div class="login-box w-[450px] bg-white shadow-lg rounded-lg overflow-hidden">

                <!-- Left Section: Brand Description -->
                <div class="left-section w-full flex flex-col items-center justify-center p-6 bg-gradient-to-r from-orange-500 to-yellow-500 text-center">
                    <h1 class="text-3xl font-bold text-white mt-4">Teh Sultan Indonesia</h1>
                    <p class="text-lg text-white mt-2">Nikmati Kesegaran Alami dari Teh Sultan Indonesia</p>
                </div>

                <!-- Right Section: Login Form -->
                <div class="right-section p-6">
                    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Login</h2>
                    <form @submit.prevent="submit">
                        <!-- Username Field -->
                        <div class="mb-4">
                            <InputLabel for="username" value="Username" />
                            <TextInput
                                id="username"
                                type="text"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                                v-model="form.username"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.username" />
                        </div>

                        <!-- Role Field -->
                        <div class="mb-4">
                            <InputLabel for="role" value="Role" />
                            <select
                                id="role"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                                v-model="form.role"
                                required
                            >
                                <option value="manager">Manager</option>
                                <option value="crewoutlet">Crew Outlet</option>
                                <option value="crewgudang">Crew Gudang</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.role" />
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <InputLabel for="password" value="Password" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="mb-4 flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="flex items-center justify-between mb-4">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-gray-600 hover:text-orange-500 underline"
                            >
                                Forgot password?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <PrimaryButton
                                class="w-full py-3 bg-orange-500 text-white rounded-md shadow-md hover:bg-orange-600 transition"
                                :class="{ 'opacity-50': form.processing }"
                                :disabled="form.processing"
                            >
                                Log in
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Main container for login page - Set background color to white */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: white; /* Set the page background to white */
}

/* Login Box (Fixed size with larger width) */
.login-box {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 450px;  /* Set a max width to make it look like a "window" */
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    height: auto;  /* Make height auto adjust based on content */
}

/* Styling for the left section (Brand section) */
.left-section {
    background: linear-gradient(to right, #ff6600, #ffcc00);
    text-align: center;
    padding: 2rem;
    color: white;
}

/* Styling for the right section (Form Section) */
.right-section {
    padding: 2rem;
}

/* Styling for Primary Button */
.primary-button {
    background-color: #ff6600;
    color: white;
    padding: 1rem;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.primary-button:hover {
    background-color: #e55b00;
}

/* Responsive Layout for smaller devices */
@media (max-width: 640px) {
    .login-box {
        width: 90%;  /* Resize the box on smaller screens */
        max-width: 400px;
        height: auto;
    }
    .left-section {
        padding: 1.5rem;
    }
    .right-section {
        padding: 1.5rem;
    }
}
</style>
