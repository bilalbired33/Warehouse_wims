<template>
  <span class="md:hidden">
    <transition mode="out-in" name="fade">
      <button v-if="show" @click="show = false" class="text-white focus:outline-none">
        <icons name="cross" />
      </button>
      <button v-else @click="show = true" class="text-white focus:outline-none">
        <icons name="menu" />
      </button>
    </transition>
  </span>
</template>

<script>
export default {
  emits: ['on-change'],
  data() {
    return {
      show: false,
    };
  },
  watch: {
    show(show) {
      this.$emit('on-change', show);
    },
  },
};
</script>
