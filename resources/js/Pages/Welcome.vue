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

<style scoped>
/* CSS untuk bagian Produk Kami */
.produk-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.produk-item {
    background-color: #fdf9f6;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.produk-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.produk-item img {
    display: block; /* Mengubah elemen menjadi blok */
    margin: auto; /* Menyelaraskan ke tengah */
    border-radius: 8px;
    max-height: 180px;
    object-fit: cover;
    margin-bottom: 1rem;
}

.produk-item h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.produk-item p {
    font-size: 1rem;
    color: #666;
    margin-bottom: 1rem;
}

.produk-item .price {
    font-size: 1.25rem;
    font-weight: bold;
    color: #ff6600;
    margin-bottom: 1rem;
}

.produk-header {
    font-size: 4xl; /* Ukuran font */
    font-family: 'serif'; /* Gaya font */
    font-weight: bold; /* Ketebalan */
    color: #333; /* Warna font */
}

.produk-item button {
    background-color: #ff6600;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.produk-item button:hover {
    background-color: #e55b00;
}

.outlet-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.outlet-item {
    background-color: #e8f4f8; 
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.outlet-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
}

.outlet-item img {
    width: 100%;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    object-fit: cover;
    height: 200px;
}

.outlet-item h3 {
    font-size: 1.75rem;
    color: #005a8c; /* Warna biru lebih gelap */
    font-weight: bold;
    margin-bottom: 1rem;
}

.outlet-item p {
    font-size: 1.1rem;
    color: #3b3b3b; /* Warna abu-abu */
    line-height: 1.6;
}

.button-container {
    display: flex;
    justify-content: center; 
    align-items: center; 
    margin-top: 2rem;
}

.lihat-outlet-button {
    background-color: #ff6600; 
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.lihat-outlet-button:hover {
    background-color: #e55b00; 
    transform: translateY(-3px); 
}
</style>

<template>
    <Head title="Teh Sultan Indonesia" />

    <div class="min-h-screen bg-[#f7e2cc]">
        <!-- Header -->
        <nav class="sticky z-50 top-0 px-12 w-full h-[80px] flex items-center justify-between bg-gradient-to-r from-orange-400 to-yellow-500">
    <div class="flex items-center">
        <img src="../../image/tehsultan.png" alt="Teh Sultan Logo" class="h-14 object-contain">
        <span class="ml-4 text-2xl font-bold text-white">Teh Sultan Indonesia</span>
    </div>
    <ul class="flex gap-4">
        <li>
            <a href="/" class="text-white hover:text-orange-500">Home</a>
        </li>
        <li>
            <a href="/produk" class="text-white hover:text-orange-500">Produk</a>
        </li>
        <li>
            <a href="#outlets" class="text-white hover:text-orange-500">Outlets</a>
        </li>
        <li>
            <a href="#about-us" class="text-white hover:text-orange-500">About Us</a>
        </li>
    </ul>

    <a v-if="authUser" href="/logout" class="bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500">Logout</a>
    <a v-else href="/login" class="bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500">Login</a>
</nav>



<section id="home" class="bg-[#f7e2cc] py-16">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 items-center">
        <!-- Gambar -->
        <div class="relative flex justify-center md:justify-start">
            <!-- Gambar Lingkaran Putih 
            <img src="../../image/white-circle.png" alt="Lingkaran Putih" 
                 class="absolute w-[350px] h-[350px] md:w-[400px] md:h-[400px] z-0 translate-x-[90px]"> -->
            <!-- Gambar PNG -->
            <img src="../../image/home1.png" alt="Teh Sultan Cups" 
            class="relative w-[1000px] md:w-[1000px] object-contain z-10 translate-x-[50px]">


        </div>

        <!-- Teks -->
        <div class="text-center md:text-left">
            <h1 class="mb-4 text-4xl font-serif font-bold text-gray-900 sm:text-5xl md:text-6xl">
                Teh Sultan Indonesia
            </h1>
            <p class="mb-6 text-lg md:text-xl text-gray-700">
                Tehnya Wong Solo. Nikmati Kesegaran Alami Dari Teh Sultan Indonesia Dengan Racikan Teh Asli Solo.
            </p>
            <a href="#produk" 
               class="inline-block rounded-lg bg-orange-500 px-6 py-3 text-white text-lg font-medium hover:bg-orange-600 transition">
                Order Now
            </a>
        </div>
    </div>
</section>




<!-- Produk Kami -->
<section id="produk" class="bg-[#f7e2cc] py-16 relative">
    <!-- Offset Scroll Fix -->
    <div class="absolute -top-20" id="produk-offset"></div>
    <div class="container mx-auto px-6 text-center">
        <h2 class="produk-header mb-8 text-4xl sm:text-5xl md:text-6xl text-black">Produk Kami</h2>
        <div class="produk-container">
            <!-- Produk 1 -->
            <div class="produk-item">
                <img src="../../image/tehlagi.png" alt="Teh Original">
                <h3><b>Teh Jumbo</b></h3>
                <p>Nikmati kesegaran teh asli Solo dengan aroma yang khas.</p>
            </div>
            <!-- Produk 2 -->
            <div class="produk-item">
                <img src="../../image/lemontea.png" alt="Teh Lemon">
                <h3><b>Teh Lemon</b></h3>
                <p>Kesegaran teh berpadu sempurna dengan rasa lemon yang menyegarkan.</p>
            </div>
            <!-- Produk 3 -->
            <div class="produk-item">
                <img src="../../image/jrk.png" alt="Es Jeruk">
                <h3><b>Es Jeruk</b></h3>
                <p>Minuman segar dengan rasa jeruk alami, memberikan sensasi kesegaran di setiap tegukan.</p>
            </div>
            <!-- Produk 4 -->
            <div class="produk-item">
                <img src="../../image/milk.png" alt="Milk Tea">
                <h3><b>Milk Tea</b></h3>
                <p>Kombinasi sempurna antara teh premium dan susu segar yang lembut dan creamy.</p>
            </div>
            <!-- Produk 5 -->
            <div class="produk-item">
                <img src="../../image/kopi1.png" alt="Es Kopi">
                <h3><b>Es Kopi</b></h3>
                <p>Kopi dingin dengan rasa yang kuat, cocok untuk pecinta kopi sejati.</p>
            </div>
            <!-- Produk 6 -->
            <div class="produk-item">
                <img src="../../image/kopi2.png" alt="Kopi Susu">
                <h3><b>Es Kopi Susu Gula Aren</b></h3>
                <p>Perpaduan kopi, susu segar, dan gula aren yang memberikan rasa manis alami.</p>
            </div>
            <!-- Produk 7 -->
            <div class="produk-item">
                <img src="../../image/kopi3.png" alt="Kopi Aren">
                <h3><b>Es Kopi Gula Aren</b></h3>
                <p>Kopi hitam dengan tambahan gula aren, menciptakan keseimbangan rasa manis dan pahit yang sempurna.</p>
            </div>
            <!-- Produk 8 -->
            <div class="produk-item">
                <img src="../../image/kopi4.png" alt="Teh Black">
                <h3><b>Kopi Hitam</b></h3>
                <p>Kopi hitam dingin yang dibuat dari biji kopi pilihan dengan aroma yang khas.</p>
            </div>
            <!-- Produk 9 -->
            <div class="produk-item">
                <img src="../../image/pentol.png" alt="Pentol">
                <h3><b>Pentol Sultan</b></h3>
                <p>Camilan pentol daging sapi dengan tekstur kenyal dan saus spesial yang menggugah selera.</p>
            </div>
        </div>
    </div>
</section>



       <!-- Ini Bagian Outlet-->
       <section id="outlets" class="bg-[#f7e2cc] py-16">
  <div class="container mx-auto px-6 text-center">
    <h2 class="produk-header mb-8 text-4xl sm:text-5xl md:text-6xl">Outlet Kami</h2>
    <div class="outlet-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Outlet 1 -->
      <div class="outlet-item">
        <img src="../../image/outlet1.jpg" alt="Outlet 1" class="w-full rounded-lg mb-4">
        <div class="outlet-info">
          <h3><b>Outlet 1</b></h3>
          <p>Jl. Garuda Mas, Nillasari, Gonilan, Kartasura, Sukoharjo</p>
        </div>
      </div>
      <!-- Outlet 2 -->
      <div class="outlet-item">
        <img src="../../image/outlet2.jpg" alt="Outlet 2" class="w-full rounded-lg mb-4">
        <div class="outlet-info">
          <h3><b>Outlet 2</b></h3>
          <p>Jl. Garuda Mas, Karangasem, Laweyan, Surakarta</p>
        </div>
      </div>
      <!-- Outlet 3 -->
      <div class="outlet-item">
        <img src="../../image/outlet3.jpg" alt="Outlet 3" class="w-full rounded-lg mb-4">
        <div class="outlet-info">
          <h3><b>Outlet 3</b></h3>
          <p>Jl. Danrilis, Blulukan, Colomadu, (Belakang Hotel Aluna)</p>
        </div>
      </div>
    </div>
    <div class="button-container">
  <a href="/outlet" class="lihat-outlet-button mt-8">Lihat Outlet Lainnya</a>
</div>

  </div>
</section>



<!-- About Us  -->
<section id="about-us" class="bg-[#f7e2cc] py-16 relative overflow-hidden">
    <!-- Gambar Daun Teh Dekoratif -->
    <img src="../../image/daun-teh.png" alt="Daun Teh Dekoratif" 
        class="absolute top-0 left-0 w-full h-full object-cover opacity-20 pointer-events-none">

    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 items-center gap-8 relative z-10">
        <!-- Gambar -->
        <div>
            <img src="../../image/tehya.png" alt="About Us Teh Sultan">
        </div>
        
        <!-- Teks -->
        <div class="text-center md:text-left">
            <h2 class="produk-header mb-4 text-4xl sm:text-5xl md:text-6xl">Tentang Teh Sultan Indonesia</h2>
            <p class="text-gray-700 text-lg sm:text-xl md:text-2xl mb-6">
                Teh Sultan Indonesia adalah minuman khas dari Solo yang dibuat dengan racikan teh asli berkualitas tinggi. 
                Dengan cita rasa yang autentik, Teh Sultan menghadirkan kesegaran alami dan pengalaman minum teh terbaik.
            </p>
            <a href="/about" class="inline-block rounded-lg bg-orange-500 px-6 py-3 text-white hover:bg-orange-600 transition">
            Selengkapnya
            </a>


        </div>
    </div>
</section>






<!-- Footer -->
<footer class="bg-gradient-to-r from-orange-400 to-yellow-500 text-white py-10">
    <div class="container mx-auto px-6">
        <!-- Navigasi -->
        <nav class="flex flex-col md:flex-row justify-between items-center border-b border-white pb-6 mb-6">
            <div class="flex items-center space-x-4 mb-4 md:mb-0">
                <img src="../../image/tehsultan.png" alt="Teh Sultan Logo" class="h-12 object-contain">
                <span class="text-white text-2xl font-bold">TEH SULTAN INDONESIA</span>
            </div>
            <ul class="flex space-x-6 text-sm font-medium">
                <li>
                    <a href="/" class="hover:text-black transition duration-300">Home</a>
                </li>
                <li>
                    <a href="#produk" class="hover:text-black transition duration-300">Product</a>
                </li>
                <li>
                    <a href="#outlets" class="hover:text-black transition duration-300">Outlets</a>
                </li>
                <li>
                    <a href="/about" class="hover:text-black transition duration-300">About Us</a>
                </li>
            </ul>
        </nav>

        <!-- Konten Footer -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kontak -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Kontak Kami</h3>
                <ul class="space-y-2">
                    <li class="flex items-center space-x-2">
                        <span>📍</span>
                        <span>Kartasura, Sukoharjo, Jawa Tengah, Indonesia</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <!-- Ganti icon kamera dengan logo Instagram -->
                        <span><img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram Logo" class="w-6 h-6"></span>
                        <span>@Tehsultanindonesia</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span>📞</span>
                        <span>+62 812 2712 5504</span>
                    </li>
                </ul>
            </div>

            <!-- Ikuti Kami -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Ikuti Kami</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="https://instagram.com/tehsultanindonesia" target="_blank" class="hover:underline">
                            Instagram
                        </a>
                    </li>
                    <li>
                        <a href="https://wa.me/6281227125504" target="_blank" class="hover:underline">
                            WhatsApp
                        </a>
                    </li>
                </ul>
            </div>


        </div>
    </div>

    <!-- Copyright -->
    <div class="text-center mt-8 pt-4 border-t border-white">
        <p class="text-sm">
            © 2024 Teh Sultan Indonesia. All rights reserved.
        </p>
    </div>
</footer>
    </div>
</template>