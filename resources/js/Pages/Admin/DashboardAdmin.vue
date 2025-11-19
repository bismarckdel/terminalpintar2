<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Dashboard Admin" />
    
    <nav class="bg-white shadow-sm sticky top-0 z-50">
      <div class="px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 bg-green-500 rounded flex items-center justify-center">
            <span class="text-white font-bold text-xs">TP</span>
          </div>
          <span class="text-2xl font-bold text-green-600">Terminal Pintar</span>
        </div>
        
        <div class="flex items-center gap-8">
          <Link href="/dashboardadmin" class="text-gray-600 hover:text-green-600 text-sm font-medium">Dashboard</Link>
          <Link href="/admin/user" class="text-gray-600 hover:text-green-600 text-sm font-medium">Kelola Pengguna</Link>
          <Link href="/admin/siswa" class="text-gray-600 hover:text-green-600 text-sm font-medium">Kelola Siswa</Link>
          <Link href="/admin/berita" class="text-gray-600 hover:text-green-600 text-sm font-medium">Berita & Dokumentasi</Link>
          <Link href="/admin/perpustakaan" class="text-gray-600 hover:text-green-600 text-sm font-medium">Perpustakaan</Link>
        </div>

        <div class="flex items-center gap-4">
          <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
            <div>
              <p class="text-sm font-medium text-gray-800">{{ user?.nama || 'Admin User' }}</p>
              <p class="text-xs text-gray-500">Administrator</p>
            </div>
          </div>
          <Link href="/logout" method="post" as="button" class="text-sm text-red-600 hover:text-red-700 font-medium">
            Logout
          </Link>
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
              <p class="text-gray-500 text-xs mt-2">{{ stats.totalSiswa }} siswa aktif</p>
            </div>
          </div>
          <Link 
            href="/admin/siswa" 
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-medium text-sm text-center block"
          >
            Akses Modul
          </Link>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-start gap-4 mb-4">
            <div class="text-4xl">🧑</div>
            <div>
              <h3 class="text-lg font-bold text-gray-800">Kelola Pengguna</h3>
              <p class="text-gray-600 text-xs mt-1">Kelola akun admin, guru, dan orang tua</p>
              <p class="text-gray-500 text-xs mt-2">{{ stats.totalUsers || 0 }} Pengguna</p>
            </div>
          </div>
          <Link 
            href="/admin/user" 
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-medium text-sm text-center block"
          >
            Akses Modul
          </Link>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-start gap-4 mb-4">
            <div class="text-4xl">📰</div>
            <div>
              <h3 class="text-lg font-bold text-gray-800">Kelola Berita & Dokumentasi</h3>
              <p class="text-gray-600 text-xs mt-1">Kelola konten berita dan galeri kegiatan</p>
              <p class="text-gray-500 text-xs mt-2">{{ stats.artikelPublished }} Artikel</p>
            </div>
          </div>
          <Link 
            href="/admin/berita" 
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-medium text-sm text-center block"
          >
            Akses Modul
          </Link>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
          <div class="flex items-start gap-4 mb-4">
            <div class="text-4xl">📚</div>
            <div>
              <h3 class="text-lg font-bold text-gray-800">Kelola Perpustakaan</h3>
              <p class="text-gray-600 text-xs mt-1">Kelola koleksi buku dan materi pembelajaran</p>
              <p class="text-gray-500 text-xs mt-2">{{ stats.koleksiBuku || 0 }} Buku</p>
            </div>
          </div>
          <Link 
            href="/admin/perpustakaan" 
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-medium text-sm text-center block"
          >
            Akses Modul
          </Link>
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
import { Head, Link } from '@inertiajs/vue3';

// Props dari backend (nanti akan diisi oleh controller)
const props = defineProps({
  user: Object,
  stats: {
    type: Object,
    default: () => ({
      totalSiswa: 128,
      siswaBaru: 12,
      kelasAktif: 8,
      kategoriKelas: 3,
      artikelPublished: 45,
      artikelBaru: 5,
      koleksiBuku: 245,
      kategoriKeb: 15
    })
  },
  daftarAktivitas: {
    type: Array,
    default: () => [
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
    ]
  }
});
</script>

<style scoped>
</style>