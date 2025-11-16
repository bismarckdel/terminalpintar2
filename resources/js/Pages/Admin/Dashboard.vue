<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
      <div class="px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 bg-green-500 rounded flex items-center justify-center">
            <span class="text-white font-bold text-xs">TP</span>
          </div>
          <span class="text-2xl font-bold text-green-600">Terminal Pintar</span>
        </div>
        
        <div class="flex items-center gap-8">
          <router-link to="/admin" class="text-gray-600 hover:text-green-600 text-sm font-medium">Dashboard</router-link>
          <router-link to="/admin/users" class="text-gray-600 hover:text-green-600 text-sm font-medium">Kelola Pengguna</router-link>
          <router-link to="/admin/students" class="text-gray-600 hover:text-green-600 text-sm font-medium">Kelola Siswa</router-link>
          <router-link to="/admin/berita" class="text-gray-600 hover:text-green-600 text-sm font-medium">Berita & Dokumentasi</router-link>
          
          <a href="#" class="text-gray-400 hover:text-green-600 text-sm font-medium" aria-disabled="true">Perpustakaan</a>
        </div>

        <div class="flex items-center gap-4">
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
            <div>
              <p class="text-sm font-medium text-gray-800">Admin User</p>
              <p class="text-xs text-gray-500">Administrator</p>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <main class="p-8">
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-green-600 mb-2">Dashboard Admin</h1>
        <p class="text-gray-600 text-sm">Selamat Datang di panel administrasi Terminal Pintar. Kelola semua aspek sistem pembelajaran dengan mudah dan efisien</p>
      </div>

      <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex justify-between items-start mb-4">
            <div>
              <p class="text-gray-600 text-xs font-medium mb-1">Total Siswa</p>
              <p class="text-4xl font-bold text-green-600">{{ stats.totalSiswa }}</p>
              <p class="text-gray-500 text-xs mt-2">+{{ stats.siswaBaru }} bulan ini</p>
            </div>
            <div class="text-4xl">👥</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex justify-between items-start mb-4">
            <div>
              <p class="text-gray-600 text-xs font-medium mb-1">Kelas Aktif</p>
              <p class="text-4xl font-bold text-green-600">{{ stats.kelasAktif }}</p>
              <p class="text-gray-500 text-xs mt-2">{{ stats.kategoriKelas }} Kategori</p>
            </div>
            <div class="text-4xl">📅</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex justify-between items-start mb-4">
            <div>
              <p class="text-gray-600 text-xs font-medium mb-1">Artikel Published</p>
              <p class="text-4xl font-bold text-green-600">{{ stats.artikelPublished }}</p>
              <p class="text-gray-500 text-xs mt-2">+{{ stats.artikelBaru }} minggu ini</p>
            </div>
            <div class="text-4xl">📰</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex justify-between items-start mb-4">
            <div>
              <p class="text-gray-600 text-xs font-medium mb-1">Koleksi Buku</p>
              <p class="text-4xl font-bold text-green-600">{{ stats.koleksiBuku }}</p>
              <p class="text-gray-500 text-xs mt-2">{{ stats.kategoriKeb }} Kategori</p>
            </div>
            <div class="text-4xl">📚</div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-start gap-4 mb-4">
            <div class="text-4xl">👥</div>
            <div>
              <h3 class="text-lg font-bold text-gray-800">Kelola Siswa</h3>
              <p class="text-gray-600 text-xs mt-1">Kelola data siswa, kelas, dan status keaktifan</p>
              <p class="text-gray-500 text-xs mt-2">125 siswa aktif</p>
            </div>
          </div>
          <router-link 
            to="/admin/students" 
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-medium text-sm text-center block"
          >
            Akses Modul
          </router-link>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-start gap-4 mb-4">
            <div class="text-4xl">🧑</div>
            <div>
              <h3 class="text-lg font-bold text-gray-800">Kelola Pengguna</h3>
              <p class="text-gray-600 text-xs mt-1">Kelola akun admin, guru, dan orang tua</p>
              <p class="text-gray-500 text-xs mt-2">24 Pengguna</p>
            </div>
          </div>
          <router-link 
            to="/admin/users" 
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-medium text-sm text-center block"
          >
            Akses Modul
          </router-link>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-start gap-4 mb-4">
            <div class="text-4xl">📰</div>
            <div>
              <h3 class="text-lg font-bold text-gray-800">Kelola Berita & Dokumentasi</h3>
              <p class="text-gray-600 text-xs mt-1">Kelola konten berita dan galeri kegiatan</p>
              <p class="text-gray-500 text-xs mt-2">15 Artikel</p>
            </div>
          </div>
          <router-link 
            to="/admin/berita" 
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-medium text-sm text-center block"
          >
            Akses Modul
          </router-link>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-start gap-4 mb-4">
            <div class="text-4xl">📚</div>
            <div>
              <h3 class="text-lg font-bold text-gray-800">Kelola Perpustakaan</h3>
              <p class="text-gray-600 text-xs mt-1">Kelola koleksi buku dan materi pembelajaran</p>
              <p class="text-gray-500 text-xs mt-2">245 Buku</p>
            </div>
          </div>
          <button 
            disabled 
            class="w-full bg-gray-400 text-white py-2 rounded font-medium text-sm cursor-not-allowed"
          >
            Akses Modul
          </button>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center gap-3 mb-6">
          <div class="text-2xl">📊</div>
          <h2 class="text-xl font-bold text-gray-800">Aktivitas Terbaru</h2>
        </div>
        <div class="space-y-4">
          <div v-for="aktivitas in daftarAktivitas" :key="aktivitas.id" class="flex items-start gap-3 pb-4 border-b last:border-b-0">
            <div class="text-2xl">{{ aktivitas.icon }}</div>
            <div class="flex-1">
              <p class="text-gray-800 text-sm font-medium">{{ aktivitas.deskripsi }}</p>
              <p class="text-gray-500 text-xs mt-1">{{ aktivitas.waktu }}</p>
            </div>
          </div>
          <div v-if="daftarAktivitas.length === 0" class="text-center py-8">
            <p class="text-gray-500 text-sm">Tidak ada aktivitas terbaru</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// (Script setup Anda sudah benar, biarkan apa adanya)
const stats = ref({
  totalSiswa: 128,
  siswaBaru: 12,
  kelasAktif: 8,
  kategoriKelas: 3,
  artikelPublished: 45,
  artikelBaru: 5,
  koleksiBuku: 245,
  kategoriKeb: 15
})

const daftarAktivitas = ref([
  {
    id: 1,
    icon: '👤',
    deskripsi: '5 Siswa baru mendaftar hari ini',
    waktu: '2 jam yang lalu'
  },
  {
    id: 2,
    icon: '📰',
    deskripsi: 'Artikel "Workshop Robotika" telah dipubilish',
    waktu: '4 jam yang lalu'
  },
  {
    id: 3,
    icon: '🧑‍🏫',
    deskripsi: '2 guru baru bergabung',
    waktu: '1 hari yang lalu'
  },
  {
    id: 4,
    icon: '📚',
    deskripsi: '15 buku baru ditambahkan ke perpustakaan',
    waktu: '3 hari yang lalu'
  }
])

async function loadStats() {
  try {
    const response = await axios.get('/api/admin/dashboard-stats')
    
    if (response.data) {
      stats.value = {
        totalSiswa: response.data.totalSiswa || 128,
        siswaBaru: response.data.siswaBaru || 12,
        kelasAktif: response.data.kelasAktif || 8,
        kategoriKelas: response.data.kategoriKelas || 3,
        artikelPublished: response.data.artikelPublished || 45,
        artikelBaru: response.data.artikelBaru || 5,
        koleksiBuku: response.data.koleksiBuku || 245,
        kategoriKeb: response.data.kategoriKeb || 15
      }
    }
  } catch (error) {
    console.log('Menggunakan data default (endpoint backend belum tersedia)')
  }
}

async function loadAktivitas() {
  try {
    const response = await axios.get('/api/admin/aktivitas-terbaru')
    
    if (response.data && response.data.length > 0) {
      daftarAktivitas.value = response.data.map(item => ({
        id: item.id,
        icon: item.icon || '📌',
        deskripsi: item.deskripsi,
        waktu: item.waktu
      }))
    }
  } catch (error) {
    console.log('Menggunakan data default aktivitas')
  }
}

onMounted(() => {
  loadStats()
  loadAktivitas()
})
</script>

<style scoped>
</style>