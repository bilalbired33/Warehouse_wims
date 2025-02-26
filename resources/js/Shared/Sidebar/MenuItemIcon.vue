<template>
  <div class="cursor-pointer z-10 flex group relative">
    <Link
      :href="href"
      class="flex items-center justify-center border-b w-16 h-12 border-darker px-2 lg:px-4 focus:bg-blue-600 hover:bg-blue-700 group-hover:bg-blue-600"
    >
      <slot name="icon">
        <icons v-if="!hideIcon" name="link"></icons>
      </slot>
      <div class="rounded-br absolute inset-x-full top-0 hidden group-hover:flex w-48 flex-wrap">
        <div class="bg-blue-600 h-12 w-48 flex items-center border-b border-darker rounded-r px-2 py-4 lg:px-4">
          <slot></slot>
        </div>
      </div>
    </Link>
  </div>
</template>

<script>
export default {
  props: ['href', 'hideIcon'],
};
</script>
