<template>
  <div class="px-4 py-1 bg-yellow-300 text-yellow-800 rounded flex items-center justify-between">
    <div class="flex items-center">
      <icons :name="icon" class="shrink-0 w-4 h-4 fill-yellow-800 mr-2" />
      <div class="font-medium">
        <slot />
      </div>
    </div>
    <button
      tabindex="-1"
      type="button"
      v-if="action"
      @click="$emit('restore')"
      class="px-4 py-2 rounded border-2 border-transparent hover:border-yellow-800 focus:border-yellow-800"
    >
      {{ $t('Restore') }}
    </button>
  </div>
</template>

<script>
export default {
  emits: ['restore'],
  props: {
    action: {
      type: Boolean,
      default: true,
    },
    icon: {
      type: String,
      default: 'trash',
    },
  },
};
</script>
