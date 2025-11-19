<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Kelola Perpustakaan - Admin" />
    
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
          <Link href="/admin/perpustakaan" class="text-green-600 text-sm font-bold border-b-2 border-green-600">Perpustakaan</Link>
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
          <h1 class="text-4xl font-bold text-green-600 mb-2">Kelola Perpustakaan</h1>
          <p class="text-gray-600 text-sm">Kelola koleksi buku dan materi pembelajaran</p>
        </div>
        <div class="flex gap-3">
          <Link 
            href="/admin/perpustakaan/sirkulasi" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            Sirkulasi
          </Link>
          <Link 
            href="/admin/perpustakaan/create" 
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Buku
          </Link>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Total Buku</p>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats?.total || 0 }}</p>
            </div>
            <div class="text-4xl">📚</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Tersedia</p>
              <p class="text-3xl font-bold text-green-600 mt-2">{{ stats?.tersedia || 0 }}</p>
            </div>
            <div class="text-4xl">✅</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Dipinjam</p>
              <p class="text-3xl font-bold text-blue-600 mt-2">{{ stats?.dipinjam || 0 }}</p>
            </div>
            <div class="text-4xl">📖</div>
          </div>
        </div>
      </div>

      <!-- Success Message -->
      <div v-if="$page.props.flash?.success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ $page.props.flash.success }}
      </div>

      <!-- Error Message -->
      <div v-if="$page.props.flash?.error || $page.props.errors?.error" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ $page.props.flash?.error || $page.props.errors?.error }}
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Search & Filter Section -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Buku</h3>
            <div class="flex items-center gap-3">
              <!-- Search Box -->
              <div class="relative">
                <input
                  v-model="search"
                  type="text"
                  placeholder="Cari judul buku..."
                  class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm w-64"
                />
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              
              <!-- Status Filter -->
              <select
                v-model="statusFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
              >
                <option value="">Semua Status</option>
                <option value="Tersedia">Tersedia</option>
                <option value="Dipinjam">Dipinjam</option>
                <option value="Hilang">Hilang</option>
              </select>
              
              <span class="text-sm text-gray-600">Total: {{ buku?.length || 0 }} buku</span>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="!buku || buku.length === 0">
                <td colspan="5" class="px-6 py-12 text-center">
                  <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-lg font-medium text-gray-600">Belum ada buku</p>
                    <p class="text-sm mt-2">Klik tombol "Tambah Buku" untuk menambahkan buku baru</p>
                  </div>
                </td>
              </tr>
              <tr v-for="(item, index) in buku" :key="item.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ item.judul }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ item.kategori || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="getStatusBadgeClass(item.status)"
                  >
                    {{ item.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex gap-2">
                    <Link 
                      :href="`/admin/perpustakaan/${item.id}/edit`"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Edit
                    </Link>
                    <button 
                      @click="handleDelete(item.id, item.judul, item.status)"
                      class="text-red-600 hover:text-red-900"
                      :disabled="item.status === 'Dipinjam'"
                      :class="{ 'opacity-50 cursor-not-allowed': item.status === 'Dipinjam' }"
                      :title="item.status === 'Dipinjam' ? 'Tidak dapat menghapus buku yang sedang dipinjam' : 'Hapus buku ini'"
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

defineProps({
  user: Object,
  buku: Array,
  stats: Object
});

const search = ref('');
const statusFilter = ref('');

// Debounced search with 500ms delay
const performSearch = debounce(() => {
  router.get('/admin/perpustakaan', {
    search: search.value,
    status: statusFilter.value
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['buku', 'stats']
  });
}, 500);

watch([search, statusFilter], () => {
  performSearch();
});

const getStatusBadgeClass = (status) => {
  const classes = {
    'Tersedia': 'bg-green-100 text-green-800',
    'Dipinjam': 'bg-blue-100 text-blue-800',
    'Hilang': 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const handleDelete = (id, judul, status) => {
  if (status === 'Dipinjam') {
    alert('Tidak dapat menghapus buku yang sedang dipinjam!\n\nSilakan kembalikan buku terlebih dahulu melalui menu Sirkulasi.');
    return;
  }
  
  if (confirm(`Apakah Anda yakin ingin menghapus buku:\n\n"${judul}"\n\nData yang dihapus tidak dapat dikembalikan!`)) {
    router.delete(`/admin/perpustakaan/${id}`);
  }
};
</script>
