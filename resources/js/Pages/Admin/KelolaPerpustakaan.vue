<template>
  <Head title="Kelola Perpustakaan - Admin" />

  <div class="space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
      <div>
        <h1 class="text-4xl font-bold text-green-600 mb-2">Kelola Perpustakaan</h1>
        <p class="text-gray-600 text-sm">Kelola koleksi buku dan materi pembelajaran</p>
      </div>

      <div class="flex flex-wrap gap-3">
          <Link 
            href="/admin/perpustakaan/sirkulasi" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            Sirkulasi
          </Link>
          <button
            type="button"
            @click="openCreateModal"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Buku
          </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
              
              <span class="text-sm text-gray-600">Halaman {{ buku?.current_page || 1 }} dari {{ buku?.last_page || 1 }}</span>
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peminjam</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="!buku?.data || buku.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-lg font-medium text-gray-600">Belum ada buku</p>
                    <p class="text-sm mt-2">Klik tombol "Tambah Buku" untuk menambahkan buku baru</p>
                  </div>
                </td>
              </tr>
              <tr v-for="(item, index) in buku?.data" :key="item.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ((buku?.current_page || 1) - 1) * (buku?.per_page || 10) + index + 1 }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ item.judul }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ item.kategori || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ item.peminjaman?.[0]?.peminjam?.nama || '-' }}
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
        <div v-if="buku?.data && buku.data.length > 0" class="mt-6 flex items-center justify-between border-t bg-white px-4 py-3 sm:px-6 rounded-lg shadow">
          <div class="flex flex-1 justify-between sm:hidden">
            <Link 
              v-if="buku.prev_page_url"
              :href="buku.prev_page_url" 
              class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              preserve-scroll
            >
              Sebelumnya
            </Link>
            <span v-else class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
              Sebelumnya
            </span>
            <Link 
              v-if="buku.next_page_url"
              :href="buku.next_page_url" 
              class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              preserve-scroll
            >
              Selanjutnya
            </Link>
            <span v-else class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
              Selanjutnya
            </span>
          </div>
          <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Menampilkan
                <span class="font-medium">{{ buku.from || 0 }}</span>
                sampai
                <span class="font-medium">{{ buku.to || 0 }}</span>
                dari
                <span class="font-medium">{{ buku.total || 0 }}</span>
                buku
              </p>
            </div>
            <div>
              <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                <!-- Previous Button -->
                <Link 
                  v-if="buku.prev_page_url"
                  :href="buku.prev_page_url"
                  class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                  preserve-scroll
                >
                  <span class="sr-only">Previous</span>
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                  </svg>
                </Link>
                <span v-else class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 bg-gray-50 cursor-not-allowed">
                  <span class="sr-only">Previous</span>
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                  </svg>
                </span>

                <!-- Page Numbers -->
                <template v-for="(page, index) in buku.links" :key="index">
                  <template v-if="index !== 0 && index !== buku.links.length - 1">
                    <Link 
                      v-if="page.url"
                      :href="page.url"
                      :class="[
                        'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0',
                        page.active 
                          ? 'z-10 bg-green-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600' 
                          : 'text-gray-900 hover:bg-gray-50'
                      ]"
                      preserve-scroll
                      v-html="page.label"
                    />
                    <span 
                      v-else
                      class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 ring-1 ring-inset ring-gray-300 bg-gray-50 cursor-not-allowed"
                      v-html="page.label"
                    />
                  </template>
                </template>

                <!-- Next Button -->
                <Link 
                  v-if="buku.next_page_url"
                  :href="buku.next_page_url"
                  class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                  preserve-scroll
                >
                  <span class="sr-only">Next</span>
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                  </svg>
                </Link>
                <span v-else class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 bg-gray-50 cursor-not-allowed">
                  <span class="sr-only">Next</span>
                  <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                  </svg>
                </span>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  <Modal
    :show="showFormModal"
    :title="modalTitle"
    variant="primary"
    maxWidth="lg"
    @close="closeFormModal"
  >
    <template #description>
      {{ isEditing ? 'Perbarui informasi buku yang sudah ada di perpustakaan.' : 'Lengkapi data buku baru untuk menambah koleksi.' }}
    </template>

    <form @submit.prevent="submitBook" class="space-y-5">
      <div>
        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
          Judul Buku <span class="text-red-500">*</span>
        </label>
        <input
          id="judul"
          v-model="bookForm.judul"
          type="text"
          required
          placeholder="Masukkan judul buku"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': bookForm.errors.judul }"
        />
        <p v-if="bookForm.errors.judul" class="mt-1 text-sm text-red-600">{{ bookForm.errors.judul }}</p>
      </div>

      <div>
        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
          Kategori
        </label>
        <input
          id="kategori"
          v-model="bookForm.kategori"
          type="text"
          placeholder="Contoh: Fiksi, Non-Fiksi, Pelajaran, dll"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': bookForm.errors.kategori }"
        />
        <p v-if="bookForm.errors.kategori" class="mt-1 text-sm text-red-600">{{ bookForm.errors.kategori }}</p>
        <p class="mt-1 text-xs text-gray-500">Kosongkan jika buku tidak memiliki kategori khusus.</p>
      </div>

      <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
          Status <span class="text-red-500">*</span>
        </label>
        <select
          id="status"
          v-model="bookForm.status"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{ 'border-red-500': bookForm.errors.status }"
        >
          <option value="Tersedia">Tersedia</option>
          <option value="Dipinjam">Dipinjam</option>
          <option value="Hilang">Hilang</option>
        </select>
        <p v-if="bookForm.errors.status" class="mt-1 text-sm text-red-600">{{ bookForm.errors.status }}</p>
      </div>

      <div
        v-if="isEditing && editingBook?.status === 'Dipinjam'"
        class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700"
      >
        Buku ini sedang dipinjam. Pastikan proses pengembalian sudah dilakukan melalui menu Sirkulasi sebelum mengubah status.
      </div>

      <div v-if="bookForm.errors.error" class="p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
        {{ bookForm.errors.error }}
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button
          type="button"
          class="px-5 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50"
          @click="closeFormModal"
          :disabled="bookForm.processing"
        >
          Batal
        </button>
        <button
          type="submit"
          class="px-5 py-2 bg-[#78AE4E] text-white rounded-full hover:bg-green-700 disabled:opacity-50"
          :disabled="bookForm.processing"
        >
          <span v-if="bookForm.processing">Memproses...</span>
          <span v-else>{{ isEditing ? 'Simpan Perubahan' : 'Simpan Buku' }}</span>
        </button>
      </div>
    </form>
  </Modal>
  <Modal
    :show="showDeleteModal"
    title="Hapus Buku"
    variant="danger"
    maxWidth="md"
    @close="closeDeleteModal"
  >
    <template #description>
      Tindakan ini akan menghapus data buku secara permanen. Pastikan buku tidak sedang dipinjam.
    </template>

    <div class="space-y-3 text-sm text-gray-700">
      <p>Judul buku: <span class="font-semibold">{{ bookToDelete?.judul }}</span></p>
      <p>Status: {{ bookToDelete?.status }}</p>
      <p>Peminjam: {{ bookToDelete?.peminjaman?.[0]?.peminjam?.nama || '-' }}</p>
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
        @click="deleteBook"
      >
        Hapus
      </button>
    </div>
  </Modal>

  <Modal
    :show="showWarningModal"
    title="Tidak Dapat Menghapus"
    variant="warning"
    maxWidth="sm"
    @close="closeWarningModal"
  >
    <template #description>
      {{ warningMessage }}
    </template>

    <div class="flex justify-end pt-4 mt-4 border-t">
      <button
        type="button"
        class="px-5 py-2 bg-amber-500 text-white rounded-full hover:bg-amber-600"
        @click="closeWarningModal"
      >
        Tutup
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
  buku: {
    type: Object,
    default: () => ({
      data: [],
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
      links: [],
      prev_page_url: null,
      next_page_url: null
    })
  },
  stats: Object
});

const search = ref('');
const statusFilter = ref('');
const showDeleteModal = ref(false);
const bookToDelete = ref(null);
const showWarningModal = ref(false);
const warningMessage = ref('');
const showFormModal = ref(false);
const isEditing = ref(false);
const modalTitle = ref('');
const editingBook = ref(null);

const bookForm = useForm({
  judul: '',
  kategori: '',
  status: 'Tersedia'
});

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

const confirmDelete = (book) => {
  if (book.status === 'Dipinjam') {
    warningMessage.value = `Buku "${book.judul}" sedang dipinjam. Kembalikan buku melalui menu Sirkulasi sebelum menghapus.`;
    showWarningModal.value = true;
    return;
  }

  bookToDelete.value = book;
  showDeleteModal.value = true;
};

const openCreateModal = () => {
  isEditing.value = false;
  modalTitle.value = 'Tambah Buku';
  editingBook.value = null;
  bookForm.reset();
  bookForm.clearErrors();
  bookForm.status = 'Tersedia';
  showFormModal.value = true;
};

const openEditModal = (book) => {
  isEditing.value = true;
  modalTitle.value = 'Edit Buku';
  editingBook.value = book;
  bookForm.reset();
  bookForm.clearErrors();
  bookForm.judul = book.judul || '';
  bookForm.kategori = book.kategori || '';
  bookForm.status = book.status || 'Tersedia';
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
  isEditing.value = false;
  editingBook.value = null;
  bookForm.transform((data) => data);
  bookForm.reset();
  bookForm.status = 'Tersedia';
  bookForm.clearErrors();
};

const submitBook = () => {
  if (isEditing.value && editingBook.value) {
    bookForm
      .transform((data) => ({
        judul: data.judul,
        kategori: data.kategori,
        status: data.status,
      }))
      .post(`/admin/perpustakaan/${editingBook.value.id}/update`, {
        preserveScroll: true,
        onSuccess: () => {
          closeFormModal();
        },
        onFinish: () => {
          bookForm.transform((data) => data);
        },
        onError: () => {
          bookForm.transform((data) => data);
        },
      });
  } else {
    bookForm
      .transform((data) => data)
      .post('/admin/perpustakaan', {
        preserveScroll: true,
        onSuccess: () => {
          closeFormModal();
        },
      });
  }
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  bookToDelete.value = null;
};

const deleteBook = () => {
  if (!bookToDelete.value) {
    return;
  }

  router.delete(`/admin/perpustakaan/${bookToDelete.value.id}`, {
    preserveScroll: true,
    onFinish: () => {
      closeDeleteModal();
    }
  });
};

const closeWarningModal = () => {
  showWarningModal.value = false;
  warningMessage.value = '';
};
</script>
