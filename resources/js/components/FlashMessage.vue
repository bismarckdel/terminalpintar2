<template>
  <Teleport to="body">
    <Transition
      enter-active-class="duration-300 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>

        <div
          class="relative z-10 rounded-xl shadow-2xl p-8 max-w-sm w-full text-center transform transition-all"
          :class="type === 'success' ? 'bg-[#E1F5E6]' : 'bg-[#E5E5E5]'"
        >
          <component :is="type === 'success' ? SuccessIcon : ErrorIcon" />

          <h3 v-if="type === 'error'" class="text-xl font-bold text-gray-800 mb-2">
            {{ message }}
          </h3>
          <div v-else>
            <h3 class="text-lg font-bold text-gray-800 leading-tight">
              {{ message }}
            </h3>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);
const type = ref('success');
const message = ref('');

const SuccessIcon = {
  template: `<div class="h-16 w-16 bg-green-600 rounded-md flex items-center justify-center mb-4 mx-auto">
    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
  </div>`
};

const ErrorIcon = {
  template: `<div class="h-16 w-16 border-4 border-gray-800 rounded-full flex items-center justify-center mb-4 mx-auto">
    <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
  </div>`
};

let timer = null;

const clearTimer = () => {
  if (timer) {
    clearTimeout(timer);
    timer = null;
  }
};

const scheduleAutoClose = () => {
  clearTimer();
  timer = setTimeout(() => {
    show.value = false;
  }, 2000);
};

watch(
  () => page.props.flash,
  (newFlash) => {
    if (newFlash?.success) {
      type.value = 'success';
      message.value = newFlash.success;
      show.value = true;
      scheduleAutoClose();
    } else if (newFlash?.error) {
      type.value = 'error';
      message.value = newFlash.error;
      show.value = true;
      scheduleAutoClose();
    } else if (!newFlash?.success && !newFlash?.error) {
      show.value = false;
      clearTimer();
    }
  },
  { deep: true, immediate: true }
);

onUnmounted(() => {
  clearTimer();
});
</script>
