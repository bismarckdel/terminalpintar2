<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="fixed inset-0 z-[9999] flex items-center justify-center">
        <!-- Background overlay -->
        <div 
          class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" 
          @click="handleClose"
        ></div>

        <!-- Modal panel -->
        <div 
          class="relative z-[10000] bg-white rounded-2xl px-8 py-10 text-center shadow-2xl max-w-md w-full mx-4"
          @click.stop
        >
          <!-- Icon -->
          <div class="mx-auto flex items-center justify-center mb-6">
            <div class="w-24 h-24 rounded-full border-8 border-gray-800 flex items-center justify-center">
              <svg class="w-12 h-12 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01" />
              </svg>
            </div>
          </div>

          <!-- Content -->
          <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">
              Pengguna Tidak Dapat Dihapus
            </h3>
            <p class="text-gray-600 text-base">
              {{ message }}
            </p>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch, onUnmounted } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Error'
  },
  message: {
    type: String,
    required: true
  },
  autoClose: {
    type: Boolean,
    default: true
  },
  duration: {
    type: Number,
    default: 2500
  }
});

const emit = defineEmits(['close']);

let timer = null;

const handleClose = () => {
  if (timer) {
    clearTimeout(timer);
    timer = null;
  }
  emit('close');
};

// Auto close when clicking outside or pressing ESC
const handleKeydown = (e) => {
  if (e.key === 'Escape' && props.show) {
    handleClose();
  }
};

watch(() => props.show, (newVal, oldVal) => {
  console.log('Modal show changed:', { newVal, oldVal, autoClose: props.autoClose, duration: props.duration }); // Debug
  
  if (newVal) {
    // Disable scroll & listen ESC
    document.addEventListener('keydown', handleKeydown);
    document.body.style.overflow = 'hidden';

    // Auto-close if enabled
    if (props.autoClose && props.duration > 0) {
      console.log(`Setting timer for auto-close in ${props.duration}ms...`); // Debug
      if (timer) {
        console.log('Clearing existing timer'); // Debug
        clearTimeout(timer);
      }
      
      timer = setTimeout(() => {
        console.log('Timer fired! Auto-closing modal...'); // Debug
        handleClose();
      }, props.duration);
      
      console.log('Timer ID:', timer); // Debug
    } else {
      console.log('Auto-close disabled or duration is 0'); // Debug
    }
  } else {
    // Cleanup
    console.log('Modal closed, cleaning up...'); // Debug
    document.removeEventListener('keydown', handleKeydown);
    document.body.style.overflow = '';
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
  }
}, { immediate: true });

// Cleanup on unmount
onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown);
  document.body.style.overflow = '';
  if (timer) {
    clearTimeout(timer);
    timer = null;
  }
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-to,
.modal-leave-from {
  opacity: 1;
}
</style>
