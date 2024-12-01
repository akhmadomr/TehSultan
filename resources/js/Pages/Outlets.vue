<template>
    <div class="min-h-screen bg-[#f7e2cc]">
        <!-- Header -->
        <nav class="sticky z-50 top-0 px-12 w-full h-[80px] flex items-center justify-between bg-gradient-to-r from-orange-400 to-yellow-500">
            <div class="flex items-center">
                <img src="../../image/tehsultan.png" alt="Teh Sultan Logo" class="h-14 object-contain">
                <span class="ml-4 text-2xl font-bold text-white">Teh Sultan Indonesia</span>
            </div>
            <ul class="flex gap-4">
                <li><a href="/" class="text-white hover:text-orange-500">Home</a></li>
                <li><a href="#produk" class="text-white hover:text-orange-500">Produk</a></li>
                <li><a href="#outlets" class="text-white hover:text-orange-500">Outlets</a></li>
                <li><a href="/about" class="text-white hover:text-orange-500">About Us</a></li>
            </ul>
            <a v-if="authUser" href="/logout" class="bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500">Logout</a>
            <a v-else href="/login" class="bg-orange-500 px-4 py-2 rounded-full text-white hover:bg-white hover:text-orange-500">Login</a>
        </nav>

        <!-- Outlet Section -->
        <section id="outlets" class="bg-[#f7e2cc] py-16">
            <div class="container mx-auto px-6 text-center">
                <h2 class="produk-header mb-8 text-4xl sm:text-5xl md:text-6xl font-bold text-orange-500">Outlet Kami</h2>
                <p class="text-lg text-gray-700 mb-12">Kunjungi salah satu dari 10 cabang kami di berbagai lokasi untuk menikmati Teh Sultan Indonesia!</p>
                <div class="outlet-container grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Outlet Loop -->
                    <div v-for="outlet in outlets" :key="outlet.id" class="outlet-item">
                        <div class="outlet-bg">
                            <div class="outlet-decoration"></div>
                        </div>
                        <div class="outlet-info">
                            <h3 class="text-2xl font-semibold text-orange-700">{{ outlet.name }}</h3>
                            <p class="text-gray-600 mt-2">{{ outlet.address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gradient-to-r from-orange-400 to-yellow-500 text-white py-10">
            <div class="container mx-auto px-6">
                <nav class="flex flex-col md:flex-row justify-between items-center border-b border-white pb-6 mb-6">
                    <div class="flex items-center space-x-4 mb-4 md:mb-0">
                        <img src="../../image/tehsultan.png" alt="Teh Sultan Logo" class="h-12 object-contain">
                        <span class="text-white text-2xl font-bold">TEH SULTAN INDONESIA</span>
                    </div>
                    <ul class="flex space-x-6 text-sm font-medium">
                        <li><a href="/" class="hover:text-black transition duration-300">Home</a></li>
                        <li><a href="#produk" class="hover:text-black transition duration-300">Product</a></li>
                        <li><a href="/outlet" class="hover:text-black transition duration-300">Outlets</a></li>
                        <li><a href="/about" class="hover:text-black transition duration-300">About Us</a></li>
                    </ul>
                </nav>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                    <div>
                        <h3 class="text-xl font-semibold mb-4">Ikuti Kami</h3>
                        <ul class="space-y-2">
                            <li><a href="https://instagram.com/tehsultanindonesia" target="_blank" class="hover:underline">Instagram</a></li>
                            <li><a href="https://wa.me/6281227125504" target="_blank" class="hover:underline">WhatsApp</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center mt-8 pt-4 border-t border-white">
                <p class="text-sm">© 2024 Teh Sultan Indonesia. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>

<script setup>
const outlets = [
    { id: 1, name: "Outlet 1", address: "Jl. Garuda Mas, Nillasari, Gonilan, Kartasura, Sukoharjo", image: "outlet1.jpg" },
    { id: 2, name: "Outlet 2", address: "Jl. Garuda Mas, Karangasem, Laweyan, Surakarta", image: "outlet2.jpg" },
    { id: 3, name: "Outlet 3", address: "Jl. Danrilis, Blulukan, Colomadu (Belakang Hotel Aluna)", image: "outlet3.jpg" },
    { id: 4, name: "Outlet 4", address: "Jl. Kartika, Ngoresan, Kec. Jebres, Surakarta", image: "outlet4.jpg" },
    { id: 5, name: "Outlet 5", address: "Jl. Surya Utama Jl. Panggung Rejo No.30, RT.1/RW.2, Jebres, Kec. Jebres, Kota Surakarta", image: "outlet5.jpg" },
    { id: 6, name: "Outlet 6", address: "Jl. Ngoresan l, RT.03/RW.22, Jebres, Kec. Jebres, Kota Surakarta", image: "outlet6.jpg" },
    { id: 7, name: "Outlet 7", address: "Jl. Slamet Riyadi, Kartasura, Sukoharjo", image: "outlet7.jpg" },
    { id: 8, name: "Outlet 8", address: "Jl. Nglawu, RT.02/RW.05, Dusun II, Langenharjo, Kec. Grogol, Kabupaten Sukoharjo", image: "outlet8.jpg" },
    { id: 9, name: "Outlet 9", address: "Jl. Merpati, Colomadu, Karanganyar", image: "outlet9.jpg" },
    { id: 10, name: "Outlet 10", address: "Bantulan, Jembungan, Kec. Banyudono, Kabupaten Boyolali", image: "outlet10.jpg" },
];

</script>

<style scoped>
.outlet-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}
.outlet-item {
    background-color: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.outlet-item:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}
.outlet-bg {
    position: relative;
    background: linear-gradient(135deg, rgba(255, 102, 0, 0.1), rgba(255, 215, 0, 0.1));
    border-radius: 8px;
    padding: 60px 0;
}
.outlet-decoration {
    position: absolute;
    top: 20%;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle, rgba(255, 165, 0, 0.3) 20%, transparent);
    border-radius: 50%;
    filter: blur(8px);
    z-index: 0;
}
.outlet-info {
    z-index: 1;
}
.outlet-item h3 {
    font-size: 1.5rem;
    color: #ff6600;
    font-weight: bold;
}
.outlet-item p {
    font-size: 1rem;
    color: #555;
    margin-top: 0.5rem;
}
</style>
