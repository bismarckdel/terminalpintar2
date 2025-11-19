<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Edit Berita - Admin" />
    
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
          <Link href="/admin/berita" class="text-green-600 text-sm font-bold border-b-2 border-green-600">Berita & Dokumentasi</Link>
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

    <main class="p-8 max-w-4xl mx-auto">
      <div class="mb-8">
        <Link href="/admin/berita" class="text-green-600 hover:text-green-700 text-sm font-medium mb-4 inline-block">
          ← Kembali ke Daftar Berita
        </Link>
        <h1 class="text-4xl font-bold text-green-600 mb-2">Edit Berita</h1>
        <p class="text-gray-600 text-sm">Ubah informasi berita atau dokumentasi kegiatan.</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-8">
        <form @submit.prevent="handleSubmit">
          
          <!-- Judul Berita -->
          <div class="mb-6">
            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
              Judul Berita <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.judul"
              id="judul" 
              type="text" 
              required
              placeholder="Masukkan judul berita"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.judul }"
            >
            <p v-if="form.errors.judul" class="mt-1 text-sm text-red-600">{{ form.errors.judul }}</p>
          </div>

          <!-- Konten Berita -->
          <div class="mb-6">
            <label for="konten" class="block text-sm font-medium text-gray-700 mb-2">
              Konten Berita <span class="text-red-500">*</span>
            </label>
            <textarea 
              v-model="form.konten"
              id="konten" 
              required
              rows="10"
              placeholder="Tulis konten berita di sini..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.konten }"
            ></textarea>
            <p v-if="form.errors.konten" class="mt-1 text-sm text-red-600">{{ form.errors.konten }}</p>
          </div>

          <!-- Foto Berita Saat Ini -->
          <div v-if="berita.gambar && !imagePreview" class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Foto Saat Ini
            </label>
            <img 
              :src="`/storage/${berita.gambar}`" 
              :alt="berita.judul"
              class="w-64 h-auto rounded-lg border border-gray-300"
            >
          </div>

          <!-- Upload Foto Baru -->
          <div class="mb-6">
            <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">
              Upload Foto Baru (Opsional)
            </label>
            <div class="mt-2">
              <input 
                @change="handleFileChange"
                id="gambar" 
                type="file" 
                accept="image/*"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                :class="{ 'border-red-500': form.errors.gambar }"
              >
            </div>
            <p v-if="form.errors.gambar" class="mt-1 text-sm text-red-600">{{ form.errors.gambar }}</p>
            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</p>
            
            <!-- Preview Image Baru -->
            <div v-if="imagePreview" class="mt-4">
              <p class="text-sm font-medium text-gray-700 mb-2">Preview Foto Baru:</p>
              <img :src="imagePreview" alt="Preview" class="w-64 h-auto rounded-lg border border-gray-300">
            </div>
          </div>

          <!-- Error Global -->
          <div v-if="form.errors.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-600">{{ form.errors.error }}</p>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-4">
            <Link 
              href="/admin/berita" 
              class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
            >
              Batal
            </Link>
            <button 
              type="submit" 
              :disabled="form.processing"
              class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium disabled:opacity-50"
            >
              {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  user: Object,
  berita: Object,
});

// Initialize form dengan data berita yang ada
const form = useForm({
  judul: props.berita.judul,
  konten: props.berita.konten,
  gambar: null,
});

const imagePreview = ref(null);

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.gambar = file;
    
    // Create preview
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const handleSubmit = () => {
  // Menggunakan POST sesuai route yang ada
  form.post(`/admin/berita/${props.berita.id}/update`, {
    preserveScroll: true,
    onSuccess: () => {
      imagePreview.value = null;
    },
  });
};
</script>
