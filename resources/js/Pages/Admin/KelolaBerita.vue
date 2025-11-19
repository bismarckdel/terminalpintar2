<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Kelola Berita - Admin" />
    
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
          <Link href="/admin/berita" class="text-green-600 text-sm font-bold border-b-2 border-green-600">Berita & Dokumentasi</Link>
          
          <span class="text-gray-400 text-sm font-medium cursor-not-allowed">Perpustakaan</span>
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
      <div class="mb-8 flex justify-between items-center">
        <div>
          <h1 class="text-4xl font-bold text-green-600 mb-2">Kelola Berita & Dokumentasi</h1>
          <p class="text-gray-600 text-sm">Kelola konten berita dan galeri kegiatan</p>
        </div>
        <Link 
          href="/admin/berita/create" 
          class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Berita
        </Link>
      </div>

      <!-- Success Message -->
      <div v-if="$page.props.flash?.success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ $page.props.flash.success }}
      </div>

      <!-- Search Box -->
      <div class="mb-6">
        <div class="relative max-w-md">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari berita berdasarkan judul atau konten..."
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
          >
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Berita</h3>
            <span class="text-sm text-gray-600">Total: {{ berita?.length || 0 }} berita</span>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="!berita || berita.length === 0">
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <p class="text-lg font-medium text-gray-600">Belum ada berita</p>
                    <p class="text-sm mt-2">Klik tombol "Tambah Berita" untuk menambahkan berita baru</p>
                  </div>
                </td>
              </tr>
              <tr v-for="(item, index) in berita" :key="item.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <img 
                    v-if="item.gambar" 
                    :src="`/storage/${item.gambar}`" 
                    :alt="item.judul"
                    class="h-16 w-24 object-cover rounded"
                  >
                  <div v-else class="h-16 w-24 bg-gray-200 rounded flex items-center justify-center">
                    <span class="text-gray-400 text-xs">No Image</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900 max-w-md truncate">{{ item.judul }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ item.penulis?.nama || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(item.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex gap-2">
                    <Link 
                      :href="`/admin/berita/${item.id}/edit`"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Edit
                    </Link>
                    <button 
                      @click="handleDelete(item.id)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
  user: Object,
  berita: Array,
  filters: Object
});

const searchQuery = ref(props.filters?.search || '');

// Debounce search untuk mengurangi request ke server
const performSearch = debounce((query) => {
  router.get('/admin/berita', 
    { search: query }, 
    { 
      preserveState: true, 
      preserveScroll: true,
      replace: true
    }
  );
}, 500);

watch(searchQuery, (newValue) => {
  performSearch(newValue);
});

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('id-ID', options);
};

const handleDelete = (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
    router.delete(`/admin/berita/${id}`);
  }
};
</script>
