<template>
  <Teleport to="body">
    <Transition leave-active-class="duration-200">
      <div
        v-if="show"
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
        scroll-region
      >
        <div
          class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
          @click="close"
        ></div>

        <div
          class="relative z-10 mx-auto flex min-h-full items-center justify-center"
        >
          <div
            class="mb-6 bg-white rounded-xl overflow-hidden shadow-2xl transform transition-all sm:w-full sm:mx-auto"
            :class="maxWidthClass"
            @click.stop
          >
          <div :class="['px-6 py-4', headerClass]">
            <h2 class="text-xl font-bold text-white">
              {{ title }}
            </h2>
            <p v-if="$slots.description" :class="['text-sm mt-1', descriptionClass]">
              <slot name="description"></slot>
            </p>
          </div>

            <div class="p-6">
              <slot />
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: ''
  },
  maxWidth: {
    type: String,
    default: '2xl'
  },
  variant: {
    type: String,
    default: 'primary'
  }
});

const emit = defineEmits(['close']);

const close = () => {
  emit('close');
};

const closeOnEscape = (event) => {
  if (event.key === 'Escape' && props.show) {
    close();
  }
};

onMounted(() => {
  document.addEventListener('keydown', closeOnEscape);
});

onUnmounted(() => {
  document.removeEventListener('keydown', closeOnEscape);
});

const maxWidthClass = computed(() => {
  const widths = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
    '3xl': 'sm:max-w-3xl'
  };

  return widths[props.maxWidth] || widths['2xl'];
});

const headerClass = computed(() => {
  const variants = {
    primary: 'bg-[#78AE4E]',
    success: 'bg-[#78AE4E]',
    danger: 'bg-red-600',
    warning: 'bg-amber-500'
  };

  return variants[props.variant] || variants.primary;
});

const descriptionClass = computed(() => {
  const colors = {
    primary: 'text-green-100',
    success: 'text-green-100',
    danger: 'text-red-100',
    warning: 'text-yellow-100'
  };

  return colors[props.variant] || colors.primary;
});
</script>
