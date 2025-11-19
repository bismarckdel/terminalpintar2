<script setup>
import { Link } from '@inertiajs/vue3'; // Gunakan Link dari Inertia

// Hardcoded data — tidak mengambil dari backend
const kegiatan = [
  {
    id: 1,
    judul: 'Workshop Literasi Digital',
    tanggal_kegiatan: '2025-10-12',
    deskripsi_singkat: 'Pelatihan dasar penggunaan internet aman untuk siswa dan orang tua.'
  },
  {
    id: 2,
    judul: 'Donasi Buku untuk Sekolah',
    tanggal_kegiatan: '2025-09-05',
    deskripsi_singkat: 'Kegiatan pengumpulan buku untuk perpustakaan sekolah lokal.'
  },
  {
    id: 3,
    judul: 'Kelas Coding Anak',
    tanggal_kegiatan: '2025-08-20',
    deskripsi_singkat: 'Pengenalan pemrograman berbasis blok untuk anak usia 10-14 tahun.'
  }
];

// Helper format tanggal (lokal, tetap digunakan untuk tampilan)
const formatTanggal = (dateString) => {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};
</script>

<template>
  <div class="landing-page font-sans">
    
    <header class="bg-white border-b border-gray-200 py-4 sticky top-0 z-50">
      <div class="px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex items-center">
          <div class="h-10 w-10 mr-3 rounded-full bg-[#76B340] flex items-center justify-center">
            <span class="text-white font-bold text-lg">TP</span>
          </div>
          <span class="text-2xl font-bold text-[#76B340]">
            Terminal Pintar
          </span>
        </div>
        
        <nav class="flex items-center space-x-6">
          <a href="#kegiatan" class="text-gray-700 font-medium hover:text-[#76B340] transition-colors">Kegiatan</a>
          <a href="#tentang" class="text-gray-700 font-medium hover:text-[#76B340] transition-colors">Tentang Kami</a>
          <a href="#kontak" class="text-gray-700 font-medium hover:text-[#76B340] transition-colors">Kontak</a>
          
          <!-- <Link 
            v-if="$page.props.auth?.user"
            href="/dashboard" 
            class="bg-[#76B340] text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-opacity-80 transition-colors"
          >
            Dashboard
          </Link> -->
          <Link 
            href="/login" 
            class="bg-[#76B340] text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-opacity-80 transition-colors"
          >
            Login
          </Link>
        </nav>
      </div>
    </header>
    
    <section 
    class="hero-section relative bg-gradient-to-r from-[#76B340] to-[#5a8f30] h-[350px] flex items-center text-white"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10 relative">
        <h1 class="text-5xl font-bold mb-4 italic">
          Halo, Selamat Datang di Terminal Pintar!
        </h1>
        <p class="text-lg font-light max-w-2xl mx-auto mb-8 italic">
          Mari bergabung bersama kami untuk belajar, berbagi inspirasi, menjadi relawan, atau berdonasi demi mendukung masa depan yang lebih baik.
        </p>
        <a href="#kegiatan" class="bg-white text-[#76B340] px-6 py-3 rounded-full text-base font-semibold hover:bg-gray-100 transition-colors">
          Jelajah lebih lanjut
        </a>
      </div>
    </section>

    <section id="kegiatan" class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[#76B340] mb-12">
          Dokumentasi Kegiatan Terbaru Kami
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          
          <div v-for="item in kegiatan" :key="item.id" class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
            <div class="h-56 w-full bg-gradient-to-br from-[#76B340] to-[#5a8f30] flex items-center justify-center">
              <span class="text-6xl">📚</span>
            </div>
            <div class="p-6 grow">
              <h3 class="font-bold text-xl mb-2">{{ item.judul }}</h3>
              <p class="text-sm text-gray-500 mb-3">{{ formatTanggal(item.tanggal_kegiatan) }}</p>
              <p class="text-gray-700 text-base mb-4">{{ item.deskripsi_singkat }}</p>
            </div>
            <div class="p-6 pt-0">
              <a href="#" class="bg-white border border-[#76B340] text-[#76B340] px-5 py-2 rounded text-sm font-semibold hover:bg-gray-50 transition-colors">Baca Selengkapnya</a>
            </div>
          </div>

          <div v-if="kegiatan.length === 0" class="col-span-3 text-center text-gray-500 py-10">
            Belum ada kegiatan terbaru.
          </div>

        </div>
        
        <div class="text-center mt-12">
          <a href="#" class="bg-[#76B340] text-white px-6 py-3 rounded text-base font-semibold hover:bg-opacity-80 transition-colors">
            Lihat semua kegiatan
          </a>
        </div>
      </div>
    </section>

    <section id="tentang" class="py-20 bg-white">
         <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-[#76B340] mb-12">Tentang Kami</h2>
            </div>
    </section>

    <section class="py-20 bg-gray-50">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-[#76B340] mb-16">Pengurus</h2>
            </div>
    </section>

    <section class="py-20 bg-white">
         <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-[#76B340] mb-12">Mari Berkontribusi!</h2>
            </div>
    </section>

    <footer id="kontak" class="bg-[#76B340] text-white pt-16 pb-8">
         <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:justify-center gap-8 md:gap-24 px-8">
            <div class="mt-12 border-t border-green-700 pt-6 text-center text-sm text-green-200">
                <p>&copy; 2025 Terminal Pintar. All rights reserved.</p>
             </div>
          </div>
    </footer>

  </div>
</template>