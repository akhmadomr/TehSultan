<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    user: Object,
});

const form = useForm({
    nama: props.user.nama,
    username: props.user.username,
    email: props.user.email,
    alamat: props.user.alamat,
    no_telp: props.user.no_telp,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const update = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            alert('Profile updated successfully');
        },
    });
};

const updatePassword = () => {
    passwordForm.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            alert('Password updated successfully');
        },
    });
};
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile Settings</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Profile Information -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Update your account's profile information.
                            </p>
                        </header>

                        <form @submit.prevent="update" class="mt-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Name</label>
                                    <input v-model="form.nama" type="text" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <div v-if="form.errors.nama" class="text-red-500 text-sm mt-1">{{ form.errors.nama }}</div>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Username</label>
                                    <input v-model="form.username" type="text" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <div v-if="form.errors.username" class="text-red-500 text-sm mt-1">{{ form.errors.username }}</div>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Email</label>
                                    <input v-model="form.email" type="email" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Phone</label>
                                    <input v-model="form.no_telp" type="text" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <div v-if="form.errors.no_telp" class="text-red-500 text-sm mt-1">{{ form.errors.no_telp }}</div>
                                </div>

                                <div class="col-span-2">
                                    <label class="block font-medium text-sm text-gray-700">Address</label>
                                    <textarea v-model="form.alamat" 
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                    <div v-if="form.errors.alamat" class="text-red-500 text-sm mt-1">{{ form.errors.alamat }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mt-4">
                                <button type="submit" 
                                        class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                        
                        <div v-if="status === 'verification-link-sent'" 
                             class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                            A verification link has been sent to your email address. 
                            Please check your email to verify the changes.
                        </div>
                        
                        <div v-if="status === 'update-verified'" 
                             class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                            Your profile has been successfully updated!
                        </div>
                    </section>
                </div>

                <!-- Change Password -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Change Password</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Ensure your account is using a secure password.
                            </p>
                        </header>

                        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Current Password</label>
                                <input v-model="passwordForm.current_password" type="password" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <div v-if="passwordForm.errors.current_password" class="text-red-500 text-sm mt-1">
                                    {{ passwordForm.errors.current_password }}
                                </div>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">New Password</label>
                                <input v-model="passwordForm.password" type="password" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <div v-if="passwordForm.errors.password" class="text-red-500 text-sm mt-1">
                                    {{ passwordForm.errors.password }}
                                </div>
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Confirm Password</label>
                                <input v-model="passwordForm.password_confirmation" type="password" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" 
                                        class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
