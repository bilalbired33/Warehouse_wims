<template>
  <div>
    <a
      @click="open = !open"
      :class="open ? 'bg-blue-600' : ''"
      class="cursor-pointer border-b border-darker flex items-center px-4 py-4 md:py-3 focus:bg-blue-600 hover:bg-blue-700"
    >
      <span class="flex items-center grow">
        <slot name="icon">
          <Icons v-if="icon" :name="icon" class="mr-3" />
          <span v-else>
            <icons v-if="open" name="open-folder" class="mr-3"></icons>
            <icons v-else name="folder" class="mr-3"></icons>
          </span>
        </slot>
        <slot></slot>
      </span>
      <icons name="chevron-down" :class="{ '-rotate-180': open }" class="transition transform duration-300"></icons>
    </a>
    <!-- <transition name="slidedown"> -->
    <div v-if="open" :class="open ? 'bg-gray-900' : 'bg-gray-800'" class="py-3 md:py-2">
      <slot name="dropdown"></slot>
    </div>
    <!-- </transition> -->
  </div>
</template>

<script>
export default {
  props: ['icon', 'active'],
  data() {
    return { open: false };
  },
  mounted() {
    this.open = this.active ? true : false;
  },
};
</script>
