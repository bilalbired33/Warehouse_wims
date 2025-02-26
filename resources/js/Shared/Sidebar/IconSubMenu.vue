<template>
  <div class="z-10 flex group relative">
    <a
      :class="{ 'bg-blue-600': active }"
      class="cursor-pointer flex items-center justify-center border-b w-16 h-12 border-darker px-2 lg:px-4 focus:bg-blue-600 hover:bg-blue-700 group-hover:bg-blue-600"
    >
      <div class="flex items-center">
        <slot name="icon">
          <icons name="folder" class="block group-hover:hidden"></icons>
          <icons name="open-folder" class="hidden group-hover:block"></icons>
        </slot>
      </div>
    </a>
    <div class="z-50 rounded-br absolute inset-x-full top-0 hidden group-hover:flex w-56 flex-wrap">
      <div class="bg-blue-600 h-12 w-56 flex items-center border-b border-darker rounded-tr px-2 lg:px-4">
        <slot></slot>
      </div>
      <div class="bg-gray-900 w-56 rounded-br flex flex-col flex-nowrap">
        <slot name="dropdown"></slot>
      </div>
    </div>
  </div>
</template>

<script>
import Icons from '../Icons.vue';
export default {
  components: { Icons },
  props: ['active'],
};
</script>
