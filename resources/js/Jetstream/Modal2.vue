<template>
  <teleport to="body">
    <transition leave-active-class="duration-200">
      <div
        v-show="show"
        :class="$settings.modal_position == 'center' ? 'items-center overflow-y-scroll' : ''"
        class="fixed flex justify-center my-auto h-screen w-full bottom-0 sm:inset-0 overflow-y-auto overflow-x-hidden sm:mx-auto sm:px-4 sm:py-6 z-50 print:m-0"
      >
        <transition
          enter-active-class="ease-out duration-300"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="ease-in duration-200"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
            <div class="absolute inset-0 bg-gray-500 opacity-75 print:bg-white print:opacity-100"></div>
          </div>
        </transition>

        <div
          class="m-auto sm:mt-0 w-full h-full print:m-0"
          :class="$settings.modal_position == 'center' ? maxWidthClass + ' flex justify-center items-center' : maxWidthClass"
        >
          <transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-90"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-90"
          >
            <div
              v-show="show"
              class="bg-white sm:rounded-lg overflow-y-visible shadow-xl transform transition-all min-w-full max-w-full w-full mt-auto sm:mx-auto sm:my-auto print:m-0"
            >
              <slot></slot>
            </div>
          </transition>
        </div>
      </div>
    </transition>
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
