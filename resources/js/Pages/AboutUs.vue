<template>
    <div class="min-h-screen bg-[#f7e2cc]">
      <!-- Header (Navigation) -->
      <nav class="sticky z-50 top-0 px-12 w-full h-[80px] flex items-center justify-between">
        <div class="flex items-center">
          <img src="../../image/tehsultan.png" alt="Teh Sultan Logo" class="h-14 object-contain">
          <span class="ml-4 text-2xl font-bold text-black">Teh Sultan Indonesia</span>
        </div>
        <ul class="flex gap-4">
          <li><a href="#home" class="text-black hover:text-orange-500">Home</a></li>
          <li><a href="#produk" class="text-black hover:text-orange-500">Produk</a></li>
          <li><a href="#outlets" class="text-black hover:text-orange-500">Outlets</a></li>
          <li><a href="#about-us" class="text-black hover:text-orange-500">About Us</a></li>
        </ul>
        
        <a v-if="authUser" href="/logout" class="bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500">Logout</a>
        <a v-else href="/login" class="bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500">Login</a>
      </nav>
  
      <!-- Main Content - About Us -->
      <section class="py-16">
        <div class="container mx-auto px-6 text-center">
          <h2 class="produk-header mb-8 text-4xl sm:text-5xl md:text-6xl">Tentang Teh Sultan Indonesia</h2>
          <p class="text-lg sm:text-xl md:text-2xl mb-6 text-gray-700">
            Teh Sultan Indonesia adalah minuman khas dari Solo yang dibuat dengan racikan teh asli berkualitas tinggi. 
            Dengan cita rasa yang autentik, Teh Sultan menghadirkan kesegaran alami dan pengalaman minum teh terbaik.
          </p>
          <img src="../../image/tehya.png" alt="Teh Sultan" class="mx-auto mb-6 rounded-xl shadow-lg">
          <p class="text-lg sm:text-xl md:text-2xl mb-6 text-gray-700">
            Kami berkomitmen untuk memberikan pengalaman teh terbaik bagi pelanggan kami dengan kualitas dan rasa yang tidak tertandingi. 
            Teh Sultan Indonesia, teh yang mengesankan.
          </p>
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
              <li><a href="#home" class="hover:text-black transition duration-300">Home</a></li>
              <li><a href="#produk" class="hover:text-black transition duration-300">Product</a></li>
              <li><a href="#outlets" class="hover:text-black transition duration-300">Outlets</a></li>
              <li><a href="#about-us" class="hover:text-black transition duration-300">About Us</a></li>
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
  // Props untuk authUser (gunakan ini jika kamu membutuhkan auth)
  defineProps({
      authUser: Object,
  });
  </script>
  
  <style scoped>
  /* Styling Header */
  .produk-header {
    font-size: 3rem;
    font-family: 'serif';
    font-weight: bold;
    color: #333;
  }
  
  /* Styling Footer */
  footer {
    background: linear-gradient(to right, #ff6600, #ffcc00);
  }
  </style>
  