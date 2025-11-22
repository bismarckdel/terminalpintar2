<template>
  <Head title="Kelola Berita - Admin" />

  <div class="space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <h1 class="text-4xl font-bold text-green-600 mb-2">Manajemen Berita dan Dokumentasi</h1>
          <p class="text-gray-600 text-sm">Kelola data berita dan galeri. Anda dapat menambah dan mengedit data berita dan galeri</p>
        </div>
        <button
          type="button"
          @click="openCreateModal"
          class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Berita
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Total Berita/Artikel</p>
              <p class="text-5xl font-bold text-green-600 mt-2">{{ stats?.totalBerita || 0 }}</p>
            </div>
            <div class="text-6xl">📄</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-sm">Foto</p>
              <p class="text-5xl font-bold text-orange-500 mt-2">{{ stats?.totalFoto || 0 }}</p>
            </div>
            <div class="text-6xl">📷</div>
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
            placeholder="Cari berita berdasarkan judul atau konten..."
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
          >
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Artikel/Berita</h3>
            <span class="text-sm text-gray-600">Halaman {{ berita?.current_page || 1 }} dari {{ berita?.last_page || 1 }}</span>
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
              <tr v-if="!berita?.data || berita.data.length === 0">
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
              <tr v-for="(item, index) in berita?.data" :key="item.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ((berita?.current_page || 1) - 1) * (berita?.per_page || 10) + index + 1 }}</td>
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
                    >
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="berita?.last_page && berita.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              Menampilkan {{ berita?.from || 0 }} sampai {{ berita?.to || 0 }} dari {{ berita?.total || 0 }} berita
            </div>
            <div class="flex gap-2">
              <div
                v-for="page in berita?.links || []"
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
    maxWidth="3xl"
    @close="closeFormModal"
  >
    <template #description>
      {{ isEditing ? 'Perbarui detail berita atau dokumentasi yang sudah ada.' : 'Tambahkan berita atau dokumentasi baru untuk dibagikan.' }}
    </template>

    <form @submit.prevent="submitBerita" class="space-y-6">
      <div>
        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
          Judul Berita <span class="text-red-500">*</span>
        </label>
        <input
          id="judul"
          v-model="beritaForm.judul"
          type="text"
          required
          placeholder="Masukkan judul berita"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': beritaForm.errors.judul }"
        />
        <p v-if="beritaForm.errors.judul" class="mt-1 text-sm text-red-600">{{ beritaForm.errors.judul }}</p>
      </div>

      <div>
        <label for="konten" class="block text-sm font-medium text-gray-700 mb-2">
          Konten Berita <span class="text-red-500">*</span>
        </label>
        <textarea
          id="konten"
          v-model="beritaForm.konten"
          rows="8"
          required
          placeholder="Tuliskan konten berita di sini..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': beritaForm.errors.konten }"
        ></textarea>
        <p v-if="beritaForm.errors.konten" class="mt-1 text-sm text-red-600">{{ beritaForm.errors.konten }}</p>
      </div>

      <div class="space-y-3">
        <label for="gambar" class="block text-sm font-medium text-gray-700">
          {{ isEditing ? 'Unggah Foto Baru (opsional)' : 'Foto Berita' }}
          <span v-if="!isEditing" class="text-red-500">*</span>
        </label>
        <input
          id="gambar"
          ref="fileInputRef"
          type="file"
          accept="image/*"
          :required="!isEditing"
          @change="handleFileChange"
          class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
          :class="{ 'border-red-500': beritaForm.errors.gambar }"
        />
        <p v-if="beritaForm.errors.gambar" class="text-sm text-red-600">{{ beritaForm.errors.gambar }}</p>
        <p class="text-xs text-gray-500">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</p>

        <div v-if="isEditing && editingBerita?.gambar && !imagePreview" class="mt-4">
          <p class="text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</p>
          <img :src="`/storage/${editingBerita.gambar}`" :alt="editingBerita.judul" class="w-64 h-auto rounded-lg border border-gray-200" />
        </div>

        <div v-if="imagePreview" class="mt-4">
          <p class="text-sm font-medium text-gray-700 mb-2">Preview Foto</p>
          <img :src="imagePreview" alt="Preview" class="w-64 h-auto rounded-lg border border-gray-200" />
        </div>
      </div>

      <div v-if="beritaForm.errors.error" class="p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
        {{ beritaForm.errors.error }}
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button
          type="button"
          class="px-5 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50"
          @click="closeFormModal"
          :disabled="beritaForm.processing"
        >
          Batal
        </button>
        <button
          type="submit"
          class="px-5 py-2 bg-[#78AE4E] text-white rounded-full hover:bg-green-700 disabled:opacity-50"
          :disabled="beritaForm.processing"
        >
          <span v-if="beritaForm.processing">Memproses...</span>
          <span v-else>{{ isEditing ? 'Simpan Perubahan' : 'Simpan Berita' }}</span>
        </button>
      </div>
    </form>
  </Modal>
  <Modal
    :show="showDeleteModal"
    title="Hapus Berita"
    variant="danger"
    maxWidth="md"
    @close="closeDeleteModal"
  >
    <template #description>
      Penghapusan berita tidak dapat dibatalkan. Pastikan informasi sudah benar.
    </template>

    <div class="space-y-3 text-sm text-gray-700">
      <p>Judul artikel: <span class="font-semibold">{{ beritaToDelete?.judul }}</span></p>
      <p>Penulis: {{ beritaToDelete?.penulis?.nama || '-' }}</p>
    </div>

    <div class="flex justify-end gap-3 pt-4 mt-6 border-t">
      <button
        type="button"
        class="px-5 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50"
        @click="closeDeleteModal"
      >
        Batal
      </button>
      <button
        type="button"
        class="px-5 py-2 bg-red-600 text-white rounded-full hover:bg-red-700"
        @click="deleteBerita"
      >
        Hapus
      </button>
    </div>
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
  berita: {
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
      totalBerita: 0,
      totalFoto: 0
    })
  },
  filters: Object
});

const searchQuery = ref(props.filters?.search || '');

const showDeleteModal = ref(false);
const beritaToDelete = ref(null);
const showFormModal = ref(false);
const isEditing = ref(false);
const modalTitle = ref('');
const editingBerita = ref(null);
const imagePreview = ref(null);
const fileInputRef = ref(null);

const beritaForm = useForm({
  judul: '',
  konten: '',
  gambar: null
});

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

const confirmDelete = (berita) => {
  beritaToDelete.value = berita;
  showDeleteModal.value = true;
};

const resetFileInput = () => {
  if (fileInputRef.value) {
    fileInputRef.value.value = '';
  }
};

const openCreateModal = () => {
  isEditing.value = false;
  modalTitle.value = 'Tambah Berita';
  editingBerita.value = null;
  beritaForm.reset();
  beritaForm.clearErrors();
  beritaForm.gambar = null;
  imagePreview.value = null;
  resetFileInput();
  showFormModal.value = true;
};

const openEditModal = (berita) => {
  isEditing.value = true;
  modalTitle.value = 'Edit Berita';
  editingBerita.value = berita;
  beritaForm.reset();
  beritaForm.clearErrors();
  beritaForm.judul = berita.judul || '';
  beritaForm.konten = berita.konten || '';
  beritaForm.gambar = null;
  imagePreview.value = null;
  resetFileInput();
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
  isEditing.value = false;
  editingBerita.value = null;
  beritaForm.transform((data) => data);
  beritaForm.reset();
  beritaForm.clearErrors();
  beritaForm.gambar = null;
  imagePreview.value = null;
  resetFileInput();
};

const handleFileChange = (event) => {
  const [file] = event.target.files;
  if (file) {
    beritaForm.gambar = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result;
    };
    reader.readAsDataURL(file);
  } else {
    beritaForm.gambar = null;
    imagePreview.value = null;
  }
};

const submitBerita = () => {
  if (isEditing.value && editingBerita.value) {
    beritaForm
      .transform((data) => ({
        judul: data.judul,
        konten: data.konten,
        gambar: data.gambar,
      }))
      .post(`/admin/berita/${editingBerita.value.id}/update`, {
        preserveScroll: true,
        onSuccess: () => {
          closeFormModal();
        },
        onFinish: () => {
          beritaForm.transform((data) => data);
        },
        onError: () => {
          beritaForm.transform((data) => data);
        },
      });
  } else {
    beritaForm
      .transform((data) => data)
      .post('/admin/berita', {
        preserveScroll: true,
        onSuccess: () => {
          closeFormModal();
        },
      });
  }
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  beritaToDelete.value = null;
};

const deleteBerita = () => {
  if (!beritaToDelete.value) {
    return;
  }

  router.delete(`/admin/berita/${beritaToDelete.value.id}`, {
    preserveScroll: true,
    onFinish: () => {
      closeDeleteModal();
    }
  });
};
</script>
