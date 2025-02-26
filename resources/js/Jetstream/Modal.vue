<template>
  <teleport to="body">
    <div v-show="show" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen text-center md:block">
        <transition
          enter-active-class="ease-out duration-300"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="ease-in duration-200"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div @click="close" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        </transition>

        <span class="hidden md:inline-block md:align-middle md:h-screen" aria-hidden="true">&#8203;</span>

        <transition
          enter-active-class="ease-out duration-300"
          enter-from-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
          enter-to-class="opacity-100 translate-y-0 md:scale-100"
          leave-active-class="ease-in duration-200"
          leave-from-class="opacity-100 translate-y-0 md:scale-100"
          leave-to-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
        >
          <div
            v-show="show"
            :class="[maxWidthClass, transparent ? '' : 'bg-white shadow-xl']"
            class="inline-block align-bottom md:rounded-md text-left transform transition-all lg:my-4 md:align-middle w-full"
          >
            <slot></slot>
          </div>
        </transition>
      </div>
    </div>
  </teleport>
</template>

<script>
import { watch, onMounted, onUnmounted } from 'vue';

export default {
  emits: ['close'],

  props: {
    show: {
      default: false,
    },
    maxWidth: {
      default: '2xl',
    },
    closeable: {
      default: true,
    },
    transparent: {
      default: false,
    },
  },

  watch: {
    show: {
      immediate: true,
      handler: show => (document.body.style.overflow = show ? 'hidden' : 'auto'),
    },
  },

  setup(props, { emit }) {
    const close = () => {
      if (props.closeable) {
        emit('close');
      }
    };

    const closeOnEscape = e => {
      if (e.key === 'Escape' && props.show) {
        close();
      }
    };

    onMounted(() => document.addEventListener('keydown', closeOnEscape));
    onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

    return {
      close,
    };
  },

  computed: {
    maxWidthClass() {
      return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '3xl': 'sm:max-w-3xl',
        '4xl': 'sm:max-w-4xl',
      }[this.maxWidth];
    },
  },
};
</script>
