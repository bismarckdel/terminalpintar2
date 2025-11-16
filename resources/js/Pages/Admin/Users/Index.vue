<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-md sticky top-0 z-40">
      <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-green-700">
          <router-link to="/">Terminal Pintar</router-link>
        </div>
        
        <div class="hidden md:flex space-x-6 items-center">
          <router-link to="/admin" class="text-gray-600 hover:text-green-700">Dashboard</router-link>
          <router-link to="/admin/users" class="text-green-700 font-semibold border-b-2 border-green-700 pb-1">Kelola Pengguna</router-link>
          <router-link to="/admin/students" class="text-gray-600 hover:text-green-700">Kelola Siswa</router-link>
          <router-link to="/admin/berita" class="text-gray-600 hover:text-green-700">Berita & Dokumentasi</router-link>
          <a href="#" class="text-gray-600 hover:text-green-700">Perpustakaan</a>
        </div>
        
        <div class="flex items-center space-x-4">
          <button class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
          </button>
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-full bg-gray-300"></div> <div class="text-sm">
              <div class="font-medium">Admin User</div>
              <div class="text-gray-500">Administrator</div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
      <div class="flex justify-between items-start mb-8">
        <div>
          <h1 class="text-4xl font-bold text-gray-800 mb-2">Manajemen Pengguna</h1>
          <p class="text-gray-600">Kelola akun pengguna Terminal Pintar. Anda dapat menambah, mengedit, dan menghapus akun pengguna</p>
        </div>
        <router-link to="/admin/users/create" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center gap-2">
          <span>➕</span> Tambah Pengguna
        </router-link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
         <div class="flex justify-between items-start">
           <div>
             <p class="text-gray-600 text-sm mb-2">Total Pengguna</p>
             <p class="text-3xl font-bold text-green-500">{{ stats.total || 0 }}</p>
           </div>
           <span class="text-3xl text-green-400">👥</span>
         </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
         <div class="flex justify-between items-start">
           <div>
             <p class="text-gray-600 text-sm mb-2">Pengguna Aktif</p>
             <p class="text-3xl font-bold text-yellow-500">{{ stats.aktif || 0 }}</p>
           </div>
           <span class="text-3xl text-yellow-400">👤</span>
         </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
         <div class="flex justify-between items-start">
           <div>
             <p class="text-gray-600 text-sm mb-2">Admin & Guru</p>
             <p class="text-3xl font-bold text-red-500">{{ stats.admin_guru || 0 }}</p>
           </div>
           <span class="text-3xl text-red-400">🛡️</span>
         </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
         <div class="flex justify-between items-start">
           <div>
             <p class="text-gray-600 text-sm mb-2">Orang Tua</p>
             <p class="text-3xl font-bold text-blue-500">{{ stats.orang_tua || 0 }}</p>
           </div>
           <span class="text-3xl text-blue-400">👨‍👩‍👧‍👦</span>
         </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
          <div class="flex items-center gap-2">
            <span class="text-green-500 text-xl">👥</span>
            <h2 class="text-xl font-bold text-gray-800">Daftar Pengguna</h2>
          </div>
          <div class="relative w-full md:w-96">
            <input
              v-model="search"
              @input="searchUsers"
              type="text"
              placeholder="Cari berdasarkan nama atau email"
              class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
            />
            <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Pengguna</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Kontak</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Peran</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Bergabung</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Login Terakhir</th>
                <th class="px-4 py-3 text-center font-semibold text-gray-700">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users.data" :key="user.id" class="border-b border-gray-100 hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-semibold">
                      {{ user.nama.charAt(0).toUpperCase() }}
                    </div>
                    <span class="font-medium text-gray-800">{{ user.nama }}</span>
                  </div>
                </td>
                <td class="px-4 py-3">
                  <div class="text-gray-600 text-sm">
                    <div>{{ user.email }}</div>
                    <div class="text-xs text-gray-400">{{ user.telepon || '—' }}</div>
                  </div>
                </td>
                
                <td class="px-4 py-3">
                  <span
                    v-if="user.role"
                    :class="[
                      'px-3 py-1 rounded-full text-xs font-semibold',
                      user.role.nama_role === 'Admin' ? 'bg-red-100 text-red-700' :
                      user.role.nama_role === 'Guru' ? 'bg-yellow-100 text-yellow-700' :
                      user.role.nama_role === 'Orang Tua' ? 'bg-green-100 text-green-700' :
                      'bg-blue-100 text-blue-700'
                    ]"
                  >
                    {{ user.role.nama_role }}
                  </span>
                  <span v-else class="text-gray-400 text-xs">Tanpa Role</span>
                </td>

                <td class="px-4 py-3">
                  <span
                    :class="[
                      'px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1 w-fit',
                      user.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                    ]"
                  >
                    <span v-if="user.is_active">✓</span>
                    <span v-else>✕</span>
                    {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                
                <td class="px-4 py-3 text-gray-600">{{ formatDate(user.created_at) }}</td>
                <td class="px-4 py-3 text-gray-600">{{ formatTime(user.last_login_at) }}</td>
                
                <td class="px-4 py-3">
                  <div class="flex justify-center gap-2">
                    <router-link :to="`/admin/users/${user.id}/edit`" class="p-2 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition" title="Edit">
                      ✎
                    </router-link>
                    <button
                      @click="deleteUser(user.id)"
                      class="p-2 bg-red-100 text-red-600 rounded hover:bg-red-200 transition"
                      title="Hapus"
                    >
                      🗑️
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
         <p class="text-sm text-gray-600">
           Menampilkan {{ users.from }}-{{ users.to }} dari {{ users.total }} Pengguna
         </p>
         <div class="flex gap-2">
           <button
             @click="previousPage"
             :disabled="!users.prev_page_url"
             class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
           >
             ← Sebelumnya
           </button>
           <div class="flex items-center gap-1">
             <span v-for="page in pages" :key="page" @click="goToPage(page)" class="px-2 py-1 border rounded cursor-pointer hover:bg-gray-50" :class="{ 'bg-green-500 text-white border-green-500': page === currentPage }">
               {{ page }}
             </span>
           </div>
           <button
             @click="nextPage"
             :disabled="!users.next_page_url"
             class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
           >
             Selanjutnya →
           </button>
         </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
// Script Anda sudah benar dan lengkap, tidak perlu diubah.
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const users = ref({ data: [], from: 1, to: 0, total: 0, per_page: 15, current_page: 1, prev_page_url: null, next_page_url: null, last_page: 1 })
const stats = ref({ total: 0, aktif: 0, admin_guru: 0, orang_tua: 0 })
const search = ref('')
const currentPage = ref(1)

const pages = computed(() => {
  const pageCount = Math.min(5, users.value.last_page)
  const result = []
  const start = Math.max(1, currentPage.value - Math.floor(pageCount / 2))
  const end = Math.min(users.value.last_page, start + pageCount - 1)

  for (let i = start; i <= end; i++) {
    result.push(i)
  }
  return result
})

async function loadStats() {
  try {
    const response = await axios.get('/api/admin/users/stats')
    stats.value = response.data
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

async function loadUsers(page = 1) {
  try {
    const response = await axios.get('/api/admin/users', {
      params: {
        page: page,
        search: search.value,
        per_page: 15
      }
    })
    users.value = response.data
    currentPage.value = page
  } catch (error) {
    console.error('Error loading users:', error)
  }
}

async function searchUsers() {
  currentPage.value = 1
  await loadUsers(1)
}

function nextPage() {
  if (users.value.next_page_url) {
    loadUsers(currentPage.value + 1)
  }
}

function previousPage() {
  if (users.value.prev_page_url) {
    loadUsers(currentPage.value - 1)
  }
}

function goToPage(page) {
  loadUsers(page)
}

async function deleteUser(id) {
  if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
    try {
      await axios.delete(`/api/admin/users/${id}`)
      await loadUsers(currentPage.value)
      await loadStats()
    } catch (error) {
      console.error('Error deleting user:', error)
    }
  }
}

function formatDate(date) {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('id-ID')
}

function formatTime(time) {
  if (!time) return '—'
  return new Date(time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
}

onMounted(() => {
  loadStats()
  loadUsers(1)
})
</script>

<style scoped>
/* Style Anda sudah benar, tidak perlu diubah. */
::-webkit-scrollbar {
  height: 8px;
}
::-webkit-scrollbar-track {
  background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}
</style>