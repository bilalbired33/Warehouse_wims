<template>
  <div>
    <jet-section-title>
      <template #title><slot name="title"></slot></template>
      <template #description><slot name="description"></slot></template>
    </jet-section-title>

    <div class="mt-6">
      <div class="px-4 py-5 md:p-6 bg-white shadow md:rounded-lg">
        <slot name="content"></slot>
      </div>
    </div>
  </div>
</template>

<script>
import JetSectionTitle from './SectionTitle.vue';

export default {
  components: {
    JetSectionTitle,
  },
};
</script>
