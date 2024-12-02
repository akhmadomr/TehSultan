<template>
  <Head title="Tentang Kami - Teh Sultan Indonesia" />

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
            <a href="/outlets" class="text-white hover:text-orange-500">Outlets</a>
        </li>
        <li>
            <a href="/about" class="text-white hover:text-orange-500">About Us</a>
        </li>
    </ul>

    <a v-if="authUser" href="/logout" class="bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500">Logout</a>
    <a v-else href="/login" class="bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500">Login</a>
</nav>

    <!-- About Us Section -->
    <section class="bg-[#f7e2cc] py-16">
      <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 items-center gap-8">
        
    <!-- Gambar -->
    <div><img src="../../image/tehsultan.png" alt="Teh Sultan Logo" class="object-contain ml-1">
      </div>

        
        <!-- Teks Tentang Kami -->
        <div>
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-serif font-bold text-gray-900 mb-6">
            Tentang Teh Sultan Indonesia
          </h1>
          <p class="text-lg sm:text-xl md:text-2xl text-gray-700 mb-6">
            Teh Sultan Indonesia adalah minuman khas dari Solo yang dibuat dengan racikan teh asli berkualitas tinggi. 
            Dengan cita rasa yang autentik, Teh Sultan menghadirkan kesegaran alami dan pengalaman minum teh terbaik.
          </p>
          <p class="text-lg sm:text-xl md:text-2xl text-gray-700 mb-6">
            Teh Sultan Indonesia mengutamakan kualitas dan rasa, menjadikan setiap tegukan teh kami sebagai pengalaman yang memanjakan lidah.
          </p>
        </div>
      </div>
    </section>

    <!-- Keunggulan Teh Sultan -->
    <section class="bg-[#f7e2cc] py-16">
      <div class="container mx-auto px-6 text-center">
        <h2 class="produk-header text-4xl sm:text-5xl md:text-6xl text-black mb-8">Keunggulan Teh Sultan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
          <div class="keunggulan-item bg-white shadow-lg rounded-lg p-8 transform hover:scale-105 transition-transform duration-300 ease-in-out">
  <img src="../../image/kualitas.png" alt="Keunggulan 1" class="w-24 h-24 mx-auto mb-6">
  <h3 class="text-2xl font-bold text-gray-900 mb-4">Kualitas Terjamin</h3>
  <p class="text-lg text-gray-700">
    Kami menggunakan teh asli berkualitas tinggi yang dipilih secara selektif untuk memastikan rasa dan aroma terbaik.
  </p>
  </div>

  <div class="keunggulan-item bg-white shadow-lg rounded-lg p-8 transform hover:scale-105 transition-transform duration-300 ease-in-out">
  <img src="../../image/alami.png" alt="Keunggulan 2" class="w-24 h-24 mx-auto mb-6">
  <h3 class="text-2xl font-bold text-gray-900 mb-4">Rasa Alami</h3>
  <p class="text-lg text-gray-700">
    Semua minuman kami dibuat dengan bahan-bahan alami, tanpa bahan pengawet, untuk memberikan rasa segar yang otentik.
  </p>
  </div>

  <div class="keunggulan-item bg-white shadow-lg rounded-lg p-8 transform hover:scale-105 transition-transform duration-300 ease-in-out">
  <img src="../../image/ahli.png" alt="Keunggulan 3" class="w-24 h-24 mx-auto mb-6">
  <h3 class="text-2xl font-bold text-gray-900 mb-4">Racikan Tangan Ahli</h3>
  <p class="text-lg text-gray-700">
    Setiap cangkir teh kami diracik dengan keahlian tinggi oleh barista berpengalaman, menjamin rasa yang sempurna setiap saat.
  </p>
</div>

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
                    <a href="/produk" class="hover:text-black transition duration-300">Product</a>
                </li>
                <li>
                    <a href="/outlet" class="hover:text-black transition duration-300">Outlets</a>
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


<script setup>
// Import Head untuk mengatur judul halaman
import { Head } from '@inertiajs/vue3';
</script>

<style scoped>
/* Custom styles for "About Us" page */
.keunggulan-item {
  background-color: #ffffff;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
}

.keunggulan-item:hover {
  transform: translateY(-10px);
}

.produk-header {
  color: #333;
  font-weight: 600;
  margin-bottom: 40px;
}

.container {
  max-width: 1200px;
}
</style>