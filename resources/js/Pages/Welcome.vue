<script setup>
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';

// Props untuk authUser, canLogin, dan canRegister
defineProps({
    authUser: Object,
    canLogin: Boolean,
    canRegister: Boolean,
});

// Fungsi untuk logout
function logout() {
    axios.post(route('logout')).then(() => {
        window.location.href = '/login'; // Redirect ke halaman login setelah logout
    }).catch(error => {
        console.error('Error logging out:', error);
    });
}
</script>

<template>
    <Head title="Teh Sultan Indonesia" />

    <div class="min-h-screen bg-[#f7e2cc]">
        <!-- Navigation -->
        <nav class="sticky z-50 top-0 px-12 w-full h-[80px] flex items-center justify-between">
            <!-- Gambar logo dan teks dibungkus dalam div dengan flex -->
            <div class="flex items-center">
                <!-- Atur tinggi gambar agar sesuai dengan navbar -->
                <img src="../../image/tehsultan.png" alt="Join Sistem Logo" class="h-14 object-contain">
                <span class="ml-4 text-2xl font-bold text-black">Teh Sultan Indonesia</span>
            </div>
            
            <ul class="flex gap-4">
                <li>
                    <a href="home" class="text-black relative hover:text-orange-500 transition duration-150 ease-in-out after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-orange-500 after:transition-all after:duration-300 hover:after:w-full">Home</a>
                </li>
                <li>
                    <a href="about" class="text-black relative hover:text-orange-500 transition duration-150 ease-in-out after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-orange-500 after:transition-all after:duration-300 hover:after:w-full">About Us</a>
                </li>
                <li>
                    <a href="delivery" class="text-black relative hover:text-orange-500 transition duration-150 ease-in-out after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-orange-500 after:transition-all after:duration-300 hover:after:w-full">Delivery</a>
                </li>
                <li>
                    <a href="location" class="text-black relative hover:text-orange-500 transition duration-150 ease-in-out after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-0 after:h-[2px] after:bg-orange-500 after:transition-all after:duration-300 hover:after:w-full">Location</a>
                </li>
            </ul>
            
            <a v-if="authUser" href="/logout" class="btn-logoutbg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500 transition duration-150 ease-in-out">Logout</a>
            <a v-else href="/login" class="btn-login bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500 transition duration-150 ease-in-out">Login</a>
            
        </nav>



        <!-- Hero Section -->
        <div class="container mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-2 items-center">
            <!-- Left: Image -->
            <div class="relative">
                <img src="../../image/home1.png" alt="Teh Sultan Cups" class="rounded-lg shadow-lg transform rotate-3">
            </div>

            <!-- Right: Text -->
            <div class="text-center md:text-left">
                <h1 class="mb-4 text-4xl font-serif font-bold text-gray-900 sm:text-5xl md:text-6xl">
                    Teh Sultan Indonesia
                </h1>
                <p class="mb-6 text-xl text-gray-700">
                    Tehnya Wong Solo. Nikmati Kesegaran Alami Dari Teh Sultan Indonesia Dengan Racikan Teh Asli Solo.
                </p>
                <Link href="#"
                    class="inline-block rounded-lg bg-orange-500 px-6 py-3 text-white hover:bg-orange-600 transition">
                    Order Now
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-100 py-8">
            <div class="container mx-auto px-6 text-center">
                <p class="text-gray-600">
                    © 2024 Teh Sultan Indonesia. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>
