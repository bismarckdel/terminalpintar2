<template>
  <Head title="Kelola Pengguna - Admin" />

  <div class="space-y-8">
      <div class="mb-8 flex justify-between items-center">
        <div>
          <h1 class="text-4xl font-bold text-green-600 mb-2">Kelola Pengguna</h1>
          <p class="text-gray-600 text-sm">Kelola akun pengguna sistem dan role mereka</p>
        </div>
        <button
          type="button"
          @click="openCreateModal"
          class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Pengguna
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Total Pengguna</p>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats?.total || 0 }}</p>
            </div>
            <div class="text-4xl">👥</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Pengguna Aktif</p>
              <p class="text-3xl font-bold text-green-600 mt-2">{{ stats?.aktif || 0 }}</p>
            </div>
            <div class="text-4xl">✅</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Admin & Guru</p>
              <p class="text-3xl font-bold text-blue-600 mt-2">{{ stats?.adminGuru || 0 }}</p>
            </div>
            <div class="text-4xl">👨‍🏫</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Orang Tua</p>
              <p class="text-3xl font-bold text-purple-600 mt-2">{{ stats?.orangtua || 0 }}</p>
            </div>
            <div class="text-4xl">👪</div>
          </div>
        </div>
      </div>

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
            placeholder="Cari pengguna berdasarkan nama..."
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
          >
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Pengguna</h3>
            <span class="text-sm text-gray-600">Halaman {{ users?.current_page || 1 }} dari {{ users?.last_page || 1 }}</span>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="!users?.data || users.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="text-lg font-medium text-gray-600">Belum ada pengguna</p>
                    <p class="text-sm mt-2">Klik tombol "Tambah Pengguna" untuk menambahkan pengguna baru</p>
                  </div>
                </td>
              </tr>
              <tr v-for="(item, index) in users?.data" :key="item.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ((users?.current_page || 1) - 1) * (users?.per_page || 10) + index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ item.nama }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ item.email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="getRoleBadgeClass(item.roles?.[0]?.name)"
                  >
                    {{ item.roles?.[0]?.name || '-' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(item.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex gap-2">
                    <button
                      type="button"
                      @click="openEditModal(item)"
                      class="text-blue-600 hover:text-blue-900 px-3 py-1 bg-blue-50 rounded"
                    >
                      Edit
                    </button>
                    <button
                      type="button"
                      @click="confirmDelete(item)"
                      class="text-red-600 hover:text-red-900 px-3 py-1 bg-red-50 rounded"
                      :disabled="item.id === user?.id"
                      :class="{ 'opacity-50 cursor-not-allowed': item.id === user?.id }"
                    >
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="users?.last_page && users.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              Menampilkan {{ users?.from || 0 }} sampai {{ users?.to || 0 }} dari {{ users?.total || 0 }} pengguna
            </div>
            <div class="flex gap-2">
              <div v-for="page in users?.links || []" :key="page.label">
                <span
                  v-if="!page.url"
                  class="px-3 py-2 text-sm rounded bg-gray-100 text-gray-400 cursor-not-allowed"
                  v-html="page.label"
                />
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

  <Modal :show="showFormModal" :title="modalTitle" :variant="isEditing ? 'primary' : 'success'" @close="closeFormModal">
    <template #description>
      {{ isEditing ? 'Perbarui informasi pengguna berikut untuk memastikan data tetap akurat.' : 'Lengkapi formulir di bawah untuk menambahkan pengguna baru ke sistem.' }}
    </template>

    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
        <input
          v-model="userForm.nama"
          type="text"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
          placeholder="Masukkan nama lengkap"
          :class="{ 'border-red-500': userForm.errors.nama }"
        />
        <p v-if="userForm.errors.nama" class="text-red-500 text-xs mt-1">{{ userForm.errors.nama }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
        <input
          v-model="userForm.email"
          type="email"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
          placeholder="Masukkan email pengguna"
          :class="{ 'border-red-500': userForm.errors.email }"
        />
        <p v-if="userForm.errors.email" class="text-red-500 text-xs mt-1">{{ userForm.errors.email }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Peran <span class="text-red-500">*</span></label>
        <select
          v-model="userForm.role"
          :disabled="isRoleLocked"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
          :class="{
            'border-red-500': userForm.errors.role,
            'bg-gray-100 cursor-not-allowed text-gray-500': isRoleLocked
          }"
        >
          <option value="">Pilih Role</option>
          <option v-for="role in availableRoles" :key="role.id" :value="role.name">{{ role.name }}</option>
        </select>
        <p v-if="userForm.errors.role" class="text-red-500 text-xs mt-1">{{ userForm.errors.role }}</p>
        <p v-if="isRoleLocked" class="text-xs text-amber-600 mt-1">
          ⚠️ Role orang tua tidak dapat diubah karena terhubung dengan data siswa.
        </p>
        <p v-else class="text-xs text-gray-500 mt-1">
          Pilih role untuk menentukan hak akses pengguna dalam sistem.
        </p>
      </div>

      <div v-if="!isEditing">
        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
        <input
          v-model="userForm.password"
          type="password"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
          placeholder="Minimal 8 karakter"
          :class="{ 'border-red-500': userForm.errors.password }"
        />
        <p v-if="userForm.errors.password" class="text-red-500 text-xs mt-1">{{ userForm.errors.password }}</p>
        <p class="text-xs text-gray-500 mt-1">Password digunakan saat pengguna pertama kali login.</p>
      </div>

      <div v-else class="p-4 rounded-lg bg-blue-50 border border-blue-200 text-sm text-blue-700">
        Password tidak dapat diubah melalui form ini. Gunakan fitur ganti password terpisah jika diperlukan.
      </div>

      <div v-if="userForm.errors.error" class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
        {{ userForm.errors.error }}
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button
          type="button"
          @click="closeFormModal"
          class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50"
        >
          Batal
        </button>
        <button
          type="submit"
          class="px-6 py-2 bg-[#78AE4E] text-white rounded-full hover:bg-green-700 disabled:opacity-50"
          :disabled="userForm.processing"
        >
          <span v-if="userForm.processing">Memproses...</span>
          <span v-else>{{ isEditing ? 'Simpan Perubahan' : 'Simpan Pengguna' }}</span>
        </button>
      </div>
    </form>
  </Modal>

  <Modal :show="showDeleteModal" title="Hapus Data Pengguna" variant="danger" maxWidth="md" @close="closeDeleteModal">
    <template #description>
      Penghapusan bersifat permanen dan tidak dapat dibatalkan. Pastikan data sudah diverifikasi.
    </template>

    <div class="space-y-4">
      <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">
        <p class="font-semibold">Anda yakin ingin menghapus pengguna ini?</p>
        <div class="mt-2 space-y-1 text-gray-700">
          <p><span class="font-semibold">Nama:</span> {{ userToDelete?.nama }}</p>
          <p><span class="font-semibold">Email:</span> {{ userToDelete?.email }}</p>
          <p><span class="font-semibold">Role:</span> {{ userToDelete?.roles?.[0]?.name || '-' }}</p>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <button
          type="button"
          @click="closeDeleteModal"
          class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50"
        >
          Batal
        </button>
        <button
          type="button"
          @click="deleteUser"
          class="px-6 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 disabled:opacity-50"
          :disabled="userToDelete?.id === user?.id"
        >
          Hapus
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash';
import Modal from '@/components/Modal.vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';

defineOptions({
  layout: AuthenticatedLayout
});

const props = defineProps({
  user: Object,
  users: {
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
      adminGuru: 0,
      orangtua: 0
    })
  },
  filters: Object,
  roles: {
    type: Array,
    default: () => []
  }
});

const searchQuery = ref(props.filters?.search || '');

const performSearch = debounce(() => {
  router.get(
    '/admin/user',
    { search: searchQuery.value },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true
    }
  );
}, 500);

watch(searchQuery, () => {
  performSearch();
});

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return date.toLocaleDateString('id-ID', options);
};

const getRoleBadgeClass = (role) => {
  const classes = {
    admin: 'bg-purple-100 text-purple-800',
    guru: 'bg-blue-100 text-blue-800',
    orangtua: 'bg-green-100 text-green-800'
  };
  return classes[role] || 'bg-gray-100 text-gray-800';
};

const userForm = useForm({
  nama: '',
  email: '',
  role: '',
  password: ''
});

const showFormModal = ref(false);
const modalTitle = ref('');
const isEditing = ref(false);
const editingUser = ref(null);

const showDeleteModal = ref(false);
const userToDelete = ref(null);

const availableRoles = computed(() => {
  if (isEditing.value && editingUser.value?.roles?.[0]?.name === 'orangtua') {
    return props.roles || [];
  }
  return (props.roles || []).filter((role) => role.name !== 'orangtua');
});

const isRoleLocked = computed(
  () => isEditing.value && editingUser.value?.roles?.[0]?.name === 'orangtua'
);

const openCreateModal = () => {
  isEditing.value = false;
  editingUser.value = null;
  modalTitle.value = 'Tambah Pengguna Baru';
  userForm.clearErrors();
  userForm.reset();
  userForm.password = '';
  showFormModal.value = true;
};

const openEditModal = (user) => {
  isEditing.value = true;
  editingUser.value = user;
  modalTitle.value = 'Edit Data Pengguna';
  userForm.clearErrors();
  userForm.reset();
  userForm.nama = user.nama || '';
  userForm.email = user.email || '';
  userForm.role = user.roles?.[0]?.name || '';
  userForm.password = '';
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
};

const submitForm = () => {
  if (isEditing.value && editingUser.value) {
    userForm.post(`/admin/user/${editingUser.value.id}/update`, {
      preserveScroll: true,
      onSuccess: () => {
        showFormModal.value = false;
        userForm.reset();
        editingUser.value = null;
      }
    });
  } else {
    userForm.post('/admin/user', {
      preserveScroll: true,
      onSuccess: () => {
        showFormModal.value = false;
        userForm.reset();
      }
    });
  }
};

const confirmDelete = (user) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  userToDelete.value = null;
};

const deleteUser = () => {
  if (!userToDelete.value) {
    return;
  }

  router.delete(`/admin/user/${userToDelete.value.id}`, {
    preserveScroll: true,
    preserveState: false,
    onSuccess: () => {
      closeDeleteModal();
    },
    onError: () => {
      closeDeleteModal();
    }
  });
};
</script>
