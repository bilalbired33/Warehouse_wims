<template>
  <div class="relative z-30">
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        @click="show = false"
        v-if="$page.props.flash.message && show"
        class="fixed top-4 right-4 w-full max-w-xs mb-8 ml-4 flex items-center justify-between bg-green-500 rounded text-white cursor-pointer"
      >
        <div class="flex items-start">
          <icons name="tick" class="ml-4 mt-4 w-4 h-4"></icons>
          <div class="p-4 font-medium">
            <div class="font-bold mb-1">{{ $t('Success!') }}</div>
            {{ $page.props.flash.message }}
          </div>
        </div>
      </div>
    </transition>

    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        @click="show = false"
        v-if="($page.props.flash.error || Object.keys($page.props.errors).length > 0) && show"
        class="fixed top-4 right-4 w-full max-w-xs mb-8 ml-4 flex items-center justify-between bg-red-500 rounded text-white cursor-pointer"
      >
        <div class="flex items-start text-white">
          <icons name="cross" class="ml-4 mt-4 w-4 h-4"></icons>
          <div v-if="$page.props.flash.error" class="p-4 font-medium" v-html="$page.props.flash.error"></div>
          <div v-else class="p-4 font-medium">
            <span v-if="Object.keys($page.props.errors).length === 1">There is one form error.</span>
            <span v-else>There are {{ Object.keys($page.props.errors).length }} form errors.</span>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show: true,
    };
  },

  watch: {
    '$page.props.flash': {
      handler() {
        this.show = true;
        this.$nextTick(() => {
          setTimeout(() => (this.show = false), 5000);
        });
      },
      deep: true,
    },
  },

  mounted() {
    setTimeout(() => (this.show = false), 5000);
  },
};
</script>
