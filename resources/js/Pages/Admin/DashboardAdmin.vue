<template>
  <Head title="Dashboard Admin" />

  <div class="space-y-8">
    <section>
      <h1 class="text-4xl font-bold text-green-600 mb-2">Dashboard Admin</h1>
      <p class="text-gray-600 text-sm">
        Selamat Datang di panel administrasi Terminal Pintar. Kelola semua aspek sistem pembelajaran dengan mudah dan efisien
      </p>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <p class="text-gray-600 text-sm font-medium mb-1">Total Siswa</p>
            <p class="text-5xl font-bold text-green-600">{{ stats.totalSiswa }}</p>
            <p class="text-gray-500 text-xs mt-2">+25 bulan ini</p>
          </div>
          <div class="text-5xl">👥</div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <p class="text-gray-600 text-sm font-medium mb-1">Artikel Published</p>
            <p class="text-5xl font-bold text-green-600">{{ stats.totalBerita }}</p>
            <p class="text-gray-500 text-xs mt-2">+5 minggu ini</p>
          </div>
          <div class="text-5xl">📄</div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <p class="text-gray-600 text-sm font-medium mb-1">Koleksi Buku</p>
            <p class="text-5xl font-bold text-green-600">{{ stats.koleksiBuku }}</p>
            <p class="text-gray-500 text-xs mt-2">15 Kategori</p>
          </div>
          <div class="text-5xl">📚</div>
        </div>
      </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
            <h3 class="text-lg font-bold text-gray-800">Kelola Berita &amp; Dokumentasi</h3>
            <p class="text-gray-600 text-xs mt-1">Kelola konten berita dan galeri kegiatan</p>
            <p class="text-gray-500 text-xs mt-2">{{ stats.totalBerita }} Artikel</p>
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
    </section>

    <section class="bg-white rounded-lg shadow-md p-6">
      <div class="flex items-center gap-3 mb-6">
        <div class="text-2xl">📊</div>
        <h2 class="text-xl font-bold text-gray-800">Aktivitas Terbaru</h2>
      </div>
      <div class="space-y-4">
        <div
          v-for="aktivitas in daftarAktivitas"
          :key="aktivitas.id"
          class="flex items-start gap-3 pb-4 border-b last:border-b-0"
        >
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
    </section>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';

defineOptions({
  layout: AuthenticatedLayout
});

// Props dari backend (nanti akan diisi oleh controller)
const props = defineProps({
  user: Object,
  stats: {
    type: Object,
    default: () => ({
      totalSiswa: 0,
      totalBerita: 0,
      koleksiBuku: 0,
      totalUsers: 0
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