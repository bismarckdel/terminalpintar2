<template>
  <Head title="Kelola Siswa - Admin" />

  <div class="space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <h1 class="text-4xl font-bold text-green-600 mb-2">Kelola Siswa</h1>
          <p class="text-gray-600 text-sm">Kelola data siswa Terminal Pintar. Anda dapat menambah dan mengedit data siswa</p>
        </div>
        <button
          type="button"
          @click="openCreateModal"
          class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Siswa
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Total Siswa</p>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats?.total || 0 }}</p>
            </div>
            <div class="text-4xl">👥</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Siswa Aktif</p>
              <p class="text-3xl font-bold text-green-600 mt-2">{{ stats?.aktif || 0 }}</p>
            </div>
            <div class="text-4xl">🟢</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Siswa Nonaktif</p>
              <p class="text-3xl font-bold text-red-600 mt-2">{{ stats?.nonaktif || 0 }}</p>
            </div>
            <div class="text-4xl">🔴</div>
          </div>
        </div>
      </div>

    <div>
        <div class="relative max-w-md">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari siswa berdasarkan nama..."
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
          >
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Siswa</h3>
            <span class="text-sm text-gray-600">Halaman {{ siswa?.current_page || 1 }} dari {{ siswa?.last_page || 1 }}</span>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Orang Tua</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Orang Tua</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="!siswa?.data || siswa.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                  <div class="flex flex-col items-center">
                    <div class="text-6xl mb-4">👥</div>
                    <p class="text-lg font-medium">Belum ada data siswa</p>
                    <p class="text-sm mt-2">Klik tombol "Tambah Siswa" untuk menambahkan siswa baru</p>
                  </div>
                </td>
              </tr>
              <tr v-for="(item, index) in siswa?.data" :key="item.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ((siswa?.current_page || 1) - 1) * (siswa?.per_page || 10) + index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ item.nama_lengkap }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ item.orang_tua?.nama || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ item.orang_tua?.email || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="item.status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ item.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center">
                    <button
                      type="button"
                      @click="openEditModal(item)"
                      class="text-blue-600 hover:text-blue-900 inline-flex items-center gap-1 px-3 py-1 bg-blue-50 rounded"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                      Edit
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="siswa?.last_page && siswa.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              Menampilkan {{ siswa?.from || 0 }} sampai {{ siswa?.to || 0 }} dari {{ siswa?.total || 0 }} siswa
            </div>
            <div class="flex gap-2">
              <div
                v-for="page in siswa?.links || []"
                :key="page.label"
              >
                <!-- Jika tidak ada URL (null), tampilkan span, bukan Link -->
                <span
                  v-if="!page.url"
                  class="px-3 py-2 text-sm rounded bg-gray-100 text-gray-400 cursor-not-allowed"
                  v-html="page.label"
                />
                
                <!-- Jika ada URL, baru gunakan Link -->
                <Link
                  v-else
                  :href="page.url"
                  :class="[
                    'px-3 py-2 text-sm rounded',
                    page.active 
                      ? 'bg-green-600 text-white font-semibold'
                      : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'
                  ]"
                  v-html="page.label"
                  preserve-scroll
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  <Modal
    :show="showFormModal"
    :title="modalTitle"
    variant="primary"
    maxWidth="xl"
    @close="closeFormModal"
  >
    <template #description>
      {{ isEditing ? 'Perbarui informasi siswa dan data orang tua.' : 'Lengkapi data siswa baru beserta akun orang tua.' }}
    </template>

    <form @submit.prevent="submitSiswa" class="space-y-5">
      <div>
        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
          Nama Lengkap Siswa <span class="text-red-500">*</span>
        </label>
        <input
          id="nama_lengkap"
          v-model="siswaForm.nama_lengkap"
          type="text"
          required
          placeholder="Masukkan nama lengkap siswa"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': siswaForm.errors.nama_lengkap }"
        />
        <p v-if="siswaForm.errors.nama_lengkap" class="mt-1 text-sm text-red-600">{{ siswaForm.errors.nama_lengkap }}</p>
      </div>

      <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
          Status <span class="text-red-500">*</span>
        </label>
        <select
          id="status"
          v-model="siswaForm.status"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': siswaForm.errors.status }"
        >
          <option value="Aktif">Aktif</option>
          <option value="Nonaktif">Nonaktif</option>
        </select>
        <p v-if="siswaForm.errors.status" class="mt-1 text-sm text-red-600">{{ siswaForm.errors.status }}</p>
      </div>

      <div class="pt-4 border-t">
        <h3 class="text-sm font-semibold text-gray-800">
          Data Orang Tua (akun login akan disesuaikan)
        </h3>
      </div>

      <div>
        <label for="nama_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
          Nama Orang Tua <span class="text-red-500">*</span>
        </label>
        <input
          id="nama_orang_tua"
          v-model="siswaForm.nama_orang_tua"
          type="text"
          required
          placeholder="Masukkan nama orang tua"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': siswaForm.errors.nama_orang_tua }"
        />
        <p v-if="siswaForm.errors.nama_orang_tua" class="mt-1 text-sm text-red-600">{{ siswaForm.errors.nama_orang_tua }}</p>
      </div>

      <div>
        <label for="email_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
          Email Orang Tua <span class="text-red-500">*</span>
        </label>
        <input
          id="email_orang_tua"
          v-model="siswaForm.email_orang_tua"
          type="email"
          required
          placeholder="contoh@email.com"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': siswaForm.errors.email_orang_tua }"
        />
        <p v-if="siswaForm.errors.email_orang_tua" class="mt-1 text-sm text-red-600">{{ siswaForm.errors.email_orang_tua }}</p>
        <p class="mt-1 text-xs text-gray-500">Email ini digunakan sebagai akun login orang tua.</p>
      </div>

      <div v-if="!isEditing">
        <label for="password_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
          Password Orang Tua <span class="text-red-500">*</span>
        </label>
        <input
          id="password_orang_tua"
          v-model="siswaForm.password_orang_tua"
          type="password"
          required
          placeholder="Minimal 8 karakter"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': siswaForm.errors.password_orang_tua }"
        />
        <p v-if="siswaForm.errors.password_orang_tua" class="mt-1 text-sm text-red-600">{{ siswaForm.errors.password_orang_tua }}</p>
        <p class="mt-1 text-xs text-gray-500">Password awal untuk akun orang tua.</p>
      </div>

      <div v-else class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
        Password akun orang tua tetap sama. Gunakan fitur reset password jika diperlukan.
      </div>

      <div v-if="siswaForm.errors.error" class="p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
        {{ siswaForm.errors.error }}
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button
          type="button"
          class="px-5 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50"
          @click="closeFormModal"
          :disabled="siswaForm.processing"
        >
          Batal
        </button>
        <button
          type="submit"
          class="px-5 py-2 bg-[#78AE4E] text-white rounded-full hover:bg-green-700 disabled:opacity-50"
          :disabled="siswaForm.processing"
        >
          <span v-if="siswaForm.processing">Memproses...</span>
          <span v-else>{{ isEditing ? 'Simpan Perubahan' : 'Simpan Siswa' }}</span>
        </button>
      </div>
    </form>
  </Modal>
</template>

<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import Modal from '@/components/Modal.vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';

defineOptions({
  layout: AuthenticatedLayout
});

const props = defineProps({
  user: Object,
  siswa: {
    type: Object,
    default: () => ({
      data: [],
      current_page: 1,
      last_page: 1,
      per_page: 10,
      from: 0,
      to: 0,
      total: 0,
      links: []
    })
  },
  stats: {
    type: Object,
    default: () => ({
      total: 0,
      aktif: 0,
      nonaktif: 0
    })
  },
  filters: Object
});

const searchQuery = ref(props.filters?.search || '');

const showFormModal = ref(false);
const isEditing = ref(false);
const modalTitle = ref('');
const editingSiswa = ref(null);

const siswaForm = useForm({
  nama_lengkap: '',
  status: 'Aktif',
  nama_orang_tua: '',
  email_orang_tua: '',
  password_orang_tua: ''
});

// Debounce search untuk mengurangi request ke server
const performSearch = debounce((query) => {
  router.get('/admin/siswa', 
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

const openCreateModal = () => {
  isEditing.value = false;
  modalTitle.value = 'Tambah Siswa';
  editingSiswa.value = null;
  siswaForm.reset();
  siswaForm.clearErrors();
  siswaForm.status = 'Aktif';
  showFormModal.value = true;
};

const openEditModal = (siswa) => {
  isEditing.value = true;
  modalTitle.value = 'Edit Data Siswa';
  editingSiswa.value = siswa;
  siswaForm.reset();
  siswaForm.clearErrors();
  siswaForm.nama_lengkap = siswa.nama_lengkap || '';
  siswaForm.status = siswa.status || 'Aktif';
  siswaForm.nama_orang_tua = siswa.orang_tua?.nama || '';
  siswaForm.email_orang_tua = siswa.orang_tua?.email || '';
  siswaForm.password_orang_tua = '';
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
  isEditing.value = false;
  editingSiswa.value = null;
  siswaForm.transform((data) => data);
  siswaForm.reset();
  siswaForm.status = 'Aktif';
  siswaForm.clearErrors();
};

const submitSiswa = () => {
  if (isEditing.value && editingSiswa.value) {
    siswaForm
      .transform((data) => ({
        nama_lengkap: data.nama_lengkap,
        status: data.status,
        nama_orang_tua: data.nama_orang_tua,
        email_orang_tua: data.email_orang_tua,
      }))
      .post(`/admin/siswa/${editingSiswa.value.id}/update`, {
        preserveScroll: true,
        onSuccess: () => {
          closeFormModal();
        },
        onFinish: () => {
          siswaForm.transform((data) => data);
        },
        onError: () => {
          siswaForm.transform((data) => data);
        },
      });
  } else {
    siswaForm
      .transform((data) => data)
      .post('/admin/siswa', {
        preserveScroll: true,
        onSuccess: () => {
          closeFormModal();
        },
      });
  }
};

</script>
