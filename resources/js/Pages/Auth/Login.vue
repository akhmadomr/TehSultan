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

        <!-- Container untuk box login -->
        <div class="login-container w-[1240px] h-[480px]">
            <!-- Box untuk dua section kiri dan kanan -->
            <div class="login-box flex">
                
                <!-- Left Section (Logo Teh Sultan) -->
                <div class="left-section w-1/2 flex flex-col items-center justify-center bg-gray-100 p-8">
                    <img src="../../../image/tehsultan.png" alt="Logo Teh Sultan Indonesia" class="w-32 h-32" />
                    <h1 class="text-2xl font-bold mt-4">Teh Sultan Indonesia</h1>
                </div>

                <!-- Garis pembatas di tengah -->
                <div class="border-l border-gray-300"></div>

                <!-- Right Section (Form Login) -->
                <div class="right-section w-1/2 p-8">
                    <h2 class="text-xl font-semibold mb-6">Login</h2>
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="username" value="Username" />
                            <TextInput
                                id="username"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.username"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.username" />
                        </div>

                        <div class="mt-4">
                    <InputLabel for="role" value="Role" />
                    <select
                        id="role"
                        class="mt-1 block w-full"
                        v-model="form.role"
                        required
                        autocomplete="current-role"
                    >
                        <option value="manager">Manager</option>
                        <option value="crewoutlet">Crew Outlet</option>
                        <option value="crewgudang">Crew Gudang</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.role" />
                </div>


                        <div class="mt-4">
                            <InputLabel for="password" value="Password" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mt-4 block">
                            <label class="flex items-center">
                                <Checkbox name="remember" v-model:checked="form.remember" />
                                <span class="ms-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>

                        <div class="mt-4 flex items-center justify-end">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Forgot password?
                            </Link>

                            <PrimaryButton
                                class="ms-4"
                                :class="{ 'opacity-25': form.processing }"
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
/* Container utama untuk mengatur layout box secara landscape */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f4f4f4;
}

/* Box utama yang membagi dua bagian kiri dan kanan */
.login-box {
    display: flex;
    width: 70%;
    max-width: 900px;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

/* Styling untuk kedua section */
.left-section, .right-section {
    padding: 2rem;
}

/* Garis pembatas */
.border-l {
    border-left-width: 2px;
    border-color: #e5e7eb;
}
</style>
