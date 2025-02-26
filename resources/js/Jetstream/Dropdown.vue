<template>
  <div class="relative">
    <div @click="open = !open" id="dd-div">
      <slot name="trigger"></slot>
    </div>

    <!-- Full Screen Dropdown Overlay -->
    <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div>

    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-show="open"
        @click="close"
        style="display: none"
        :class="[widthClass, alignmentClasses]"
        class="absolute z-50 my-2 rounded-md shadow-lg"
      >
        <div class="rounded-md ring-1 ring-black ring-opacity-5" :class="contentClasses">
          <slot name="content"></slot>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { onMounted, onUnmounted, ref } from 'vue';

export default {
  props: {
    align: {
      default: 'right',
    },
    width: {
      default: '48',
    },
    contentClasses: {
      default: () => ['py-2', 'bg-white'],
    },
    closeOnClick: {
      default: true,
    },
  },

  // watch: {
  //   open: {
  //     immediate: true,
  //     handler: open => {
  //       setTimeout(() => {
  //         document.getElementById('dd-table').style.overflow = open ? 'visible' : 'auto';
  //       }, 50);
  //     },
  //   },
  // },

  setup() {
    let open = ref(false);

    const closeOnEscape = e => {
      if (open.value && e.keyCode === 27) {
        open.value = false;
      }
    };

    onMounted(() => document.addEventListener('keydown', closeOnEscape));
    onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

    return {
      open,
    };
  },

  computed: {
    widthClass() {
      return {
        40: 'w-40',
        48: 'w-48',
        56: 'w-56',
        64: 'w-64',
      }[this.width.toString()];
    },

    alignmentClasses() {
      if (this.align === 'left') {
        return 'origin-top-left left-0';
      } else if (this.align === 'right') {
        return 'origin-top-right right-0';
      } else if (this.align === 'top-right') {
        return 'origin-bottom-right right-0 bottom-100';
      } else {
        return 'origin-top';
      }
    },
  },

  methods: {
    close() {
      if (this.closeOnClick) {
        this.open = false;
      }
    },
  },
};
</script>
